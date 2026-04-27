<?php

namespace Tests\Feature\Api;

use App\Models\Role;
use App\Models\User;
use App\Models\Customer;
use App\Models\Company;
use App\Models\CustomerAddress;
use App\Models\CustomerContact;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class OrderApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Customer $customer;
    protected Company $company;
    protected CustomerAddress $address;
    protected CustomerContact $contact;
    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::create(['name' => 'فروش']);

        $this->user = User::create([
            'full_name' => 'کاربر تست',
            'username'  => 'order_api_user',
            'password'  => bcrypt('password'),
            'role_id'   => $role->id,
            'status'    => 'active',
        ]);

        $this->customer = Customer::create(['name' => 'مشتری تست', 'status' => 'active']);
        $this->company  = Company::create(['name' => 'شرکت تست']);
        $this->address  = CustomerAddress::create([
            'customer_id'  => $this->customer->id,
            'title'        => 'دفتر',
            'full_address' => 'آدرس تست',
            'is_default'   => true,
        ]);
        $this->contact = CustomerContact::create([
            'address_id' => $this->address->id,
            'full_name'  => 'گیرنده تست',
            'mobile'     => '09130000000',
        ]);
        $this->product = Product::create([
            'name'       => 'بتن M30',
            'base_price' => 500000,
            'status'     => 'active',
        ]);
    }

    private function orderPayload(array $override = []): array
    {
        return array_merge([
            'customer_id' => $this->customer->id,
            'company_id'  => $this->company->id,
            'address_id'  => $this->address->id,
            'contact_id'  => $this->contact->id,
            'is_official' => true,
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'quantity'   => 2,
                    'price'      => 500000,
                ],
            ],
        ], $override);
    }

    #[Test]
    public function unauthenticated_user_cannot_access_orders()
    {
        $this->getJson('/api/v1/orders')->assertStatus(401);
        $this->postJson('/api/v1/orders', [])->assertStatus(401);
    }

    #[Test]
    public function authenticated_user_can_create_order()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/v1/orders', $this->orderPayload());

        $response->assertStatus(201)
            ->assertJsonStructure(['id', 'status', 'total_price', 'items']);

        $this->assertDatabaseHas('orders', [
            'customer_id' => $this->customer->id,
            'status'      => 'pending',
        ]);
    }

    #[Test]
    public function order_creation_requires_items()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/v1/orders', $this->orderPayload(['items' => []]));

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['items']);
    }

    #[Test]
    public function order_creation_validates_product_exists()
    {
        $payload = $this->orderPayload([
            'items' => [
                ['product_id' => 9999, 'quantity' => 1, 'price' => 100000],
            ],
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/v1/orders', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['items.0.product_id']);
    }

    #[Test]
    public function authenticated_user_can_get_order_list()
    {
        $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/v1/orders', $this->orderPayload());

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/v1/orders');

        $response->assertStatus(200)
            ->assertJsonCount(1);
    }

    #[Test]
    public function authenticated_user_can_get_order_detail()
    {
        $created = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/v1/orders', $this->orderPayload())
            ->json();

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/v1/orders/' . $created['id']);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id', 'status', 'total_price',
                'customer', 'company', 'items',
            ]);
    }

    #[Test]
    public function total_price_is_calculated_correctly()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/v1/orders', $this->orderPayload());

        $response->assertStatus(201)
            ->assertJsonPath('total_price', '1000000.00');
    }
}
