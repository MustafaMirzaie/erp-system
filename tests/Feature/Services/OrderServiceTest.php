<?php

namespace Tests\Feature\Services;

use App\Models\Role;
use App\Models\User;
use App\Models\Customer;
use App\Models\Company;
use App\Models\CustomerAddress;
use App\Models\CustomerContact;
use App\Models\Product;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class OrderServiceTest extends TestCase
{
    use RefreshDatabase;

    protected OrderService $service;
    protected User $user;
    protected Customer $customer;
    protected Company $company;
    protected CustomerAddress $address;
    protected CustomerContact $contact;
    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(OrderService::class);

        $role = Role::create(['name' => 'فروش']);

        $this->user = User::create([
            'full_name' => 'کاربر تست',
            'username'  => 'order_user',
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

        // لاگین کاربر برای auth()->id()
        $this->actingAs($this->user);
    }

    #[Test]
    public function it_creates_order_with_items()
    {
        $data = [
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
        ];

        $order = $this->service->createOrder($data);

        $this->assertInstanceOf(Order::class, $order);
        $this->assertDatabaseHas('orders', [
            'customer_id' => $this->customer->id,
            'status'      => 'pending',
        ]);
        $this->assertDatabaseHas('order_items', [
            'order_id'    => $order->id,
            'product_id'  => $this->product->id,
            'quantity'    => 2,
        ]);
    }

    #[Test]
    public function it_calculates_total_price_correctly()
    {
        $data = [
            'customer_id' => $this->customer->id,
            'company_id'  => $this->company->id,
            'address_id'  => $this->address->id,
            'contact_id'  => $this->contact->id,
            'is_official' => true,
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'quantity'   => 3,
                    'price'      => 500000,
                ],
            ],
        ];

        $order = $this->service->createOrder($data);

        $this->assertEquals(1500000, $order->total_price);
    }

    #[Test]
    public function it_creates_order_with_multiple_items()
    {
        $product2 = Product::create([
            'name'       => 'ملات سیمان',
            'base_price' => 200000,
            'status'     => 'active',
        ]);

        $data = [
            'customer_id' => $this->customer->id,
            'company_id'  => $this->company->id,
            'address_id'  => $this->address->id,
            'contact_id'  => $this->contact->id,
            'is_official' => true,
            'items' => [
                ['product_id' => $this->product->id, 'quantity' => 2, 'price' => 500000],
                ['product_id' => $product2->id,      'quantity' => 5, 'price' => 200000],
            ],
        ];

        $order = $this->service->createOrder($data);

        $this->assertCount(2, $order->items);
        $this->assertEquals(2000000, $order->total_price); // 1000000 + 1000000
    }

    #[Test]
    public function it_retrieves_order_with_relations()
    {
        $data = [
            'customer_id' => $this->customer->id,
            'company_id'  => $this->company->id,
            'address_id'  => $this->address->id,
            'contact_id'  => $this->contact->id,
            'is_official' => true,
            'items' => [
                ['product_id' => $this->product->id, 'quantity' => 1, 'price' => 500000],
            ],
        ];

        $created = $this->service->createOrder($data);
        $fetched = $this->service->getOrder($created->id);

        $this->assertNotNull($fetched->customer);
        $this->assertNotNull($fetched->company);
        $this->assertCount(1, $fetched->items);
    }
}
