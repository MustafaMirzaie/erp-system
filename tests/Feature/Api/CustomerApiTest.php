<?php

namespace Tests\Feature\Api;

use App\Models\Role;
use App\Models\User;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\CustomerContact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CustomerApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::create(['name' => 'فروش']);

        $this->user = User::create([
            'full_name' => 'کاربر تست',
            'username'  => 'customer_api_user',
            'password'  => bcrypt('password'),
            'role_id'   => $role->id,
            'status'    => 'active',
        ]);
    }

    private function makeCustomer(array $override = []): Customer
    {
        return Customer::create(array_merge([
            'name'   => 'شرکت تست',
            'status' => 'active',
        ], $override));
    }

    #[Test]
    public function unauthenticated_user_cannot_access_customers()
    {
        $this->getJson('/api/v1/customers')->assertStatus(401);
    }

    #[Test]
    public function authenticated_user_can_get_customer_list()
    {
        $this->makeCustomer(['name' => 'شرکت الف']);
        $this->makeCustomer(['name' => 'شرکت ب']);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/v1/customers');

        $response->assertStatus(200)
            ->assertJsonCount(2);
    }

    #[Test]
    public function inactive_customers_are_excluded_from_list()
    {
        $this->makeCustomer(['name' => 'شرکت فعال', 'status' => 'active']);
        $this->makeCustomer(['name' => 'شرکت غیرفعال', 'status' => 'inactive']);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/v1/customers');

        $response->assertStatus(200)
            ->assertJsonCount(1);
    }

    #[Test]
    public function authenticated_user_can_search_customers()
    {
        $this->makeCustomer(['name' => 'شرکت آریا']);
        $this->makeCustomer(['name' => 'شرکت پارس']);
        $this->makeCustomer(['name' => 'تعاونی آریا']);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/v1/customers?search=آریا');

        $response->assertStatus(200)
            ->assertJsonCount(2);
    }

    #[Test]
    public function authenticated_user_can_get_customer_detail()
    {
        $customer = $this->makeCustomer();

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/v1/customers/' . $customer->id);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id', 'name', 'status',
                'addresses',
            ]);
    }

    #[Test]
    public function authenticated_user_can_get_customer_addresses()
    {
        $customer = $this->makeCustomer();

        $address = CustomerAddress::create([
            'customer_id'  => $customer->id,
            'title'        => 'دفتر مرکزی',
            'full_address' => 'تهران، خیابان ولیعصر',
            'is_default'   => true,
        ]);

        CustomerContact::create([
            'address_id' => $address->id,
            'full_name'  => 'علی رضایی',
            'mobile'     => '09120000000',
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/v1/customers/' . $customer->id . '/addresses');

        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonStructure([
                '*' => ['id', 'title', 'full_address', 'contacts'],
            ]);
    }

    #[Test]
    public function customer_addresses_include_contacts()
    {
        $customer = $this->makeCustomer();

        $address = CustomerAddress::create([
            'customer_id'  => $customer->id,
            'title'        => 'انبار',
            'full_address' => 'کرج، جاده مخصوص',
            'is_default'   => false,
        ]);

        CustomerContact::create([
            'address_id' => $address->id,
            'full_name'  => 'حسن محمدی',
            'mobile'     => '09130000000',
        ]);

        CustomerContact::create([
            'address_id' => $address->id,
            'full_name'  => 'رضا کریمی',
            'mobile'     => '09140000000',
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/v1/customers/' . $customer->id . '/addresses');

        $contacts = $response->json('0.contacts');
        $this->assertCount(2, $contacts);
    }
}
