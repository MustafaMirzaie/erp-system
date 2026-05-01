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
use App\Models\WorkflowStep;
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
    protected Role $role;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(OrderService::class);

        $this->role = Role::create(['name' => 'فروش']);

        $this->user = User::create([
            'full_name' => 'کاربر تست',
            'username'  => 'order_user',
            'password'  => bcrypt('password'),
            'role_id'   => $this->role->id,
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

        $this->actingAs($this->user);
    }

    private function makeOrderData(array $override = []): array
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
    public function it_creates_order_with_items()
    {
        $order = $this->service->createOrder($this->makeOrderData());

        $this->assertInstanceOf(Order::class, $order);
        $this->assertDatabaseHas('orders', [
            'customer_id' => $this->customer->id,
            'status'      => 'pending',
        ]);
        $this->assertDatabaseHas('order_items', [
            'order_id'   => $order->id,
            'product_id' => $this->product->id,
            'quantity'   => 2,
        ]);
    }

    #[Test]
    public function it_calculates_total_price_correctly()
    {
        $order = $this->service->createOrder($this->makeOrderData([
            'items' => [
                ['product_id' => $this->product->id, 'quantity' => 3, 'price' => 500000],
            ],
        ]));

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

        $order = $this->service->createOrder($this->makeOrderData([
            'items' => [
                ['product_id' => $this->product->id, 'quantity' => 2, 'price' => 500000],
                ['product_id' => $product2->id,      'quantity' => 5, 'price' => 200000],
            ],
        ]));

        $this->assertCount(2, $order->items);
        $this->assertEquals(2000000, $order->total_price);
    }

    #[Test]
    public function it_retrieves_order_with_relations()
    {
        $created = $this->service->createOrder($this->makeOrderData());
        $fetched = $this->service->getOrder($created->id);

        $this->assertNotNull($fetched->customer);
        $this->assertNotNull($fetched->company);
        $this->assertCount(1, $fetched->items);
    }

    #[Test]
    public function it_creates_workflow_approvals_automatically()
    {
        // ساخت workflow steps
        WorkflowStep::create(['name' => 'تایید مدیر فروش',  'step_order' => 1, 'role_id' => $this->role->id]);
        WorkflowStep::create(['name' => 'تایید مدیر مالی',  'step_order' => 2, 'role_id' => $this->role->id]);

        $order = $this->service->createOrder($this->makeOrderData());

        $this->assertDatabaseHas('order_approvals', [
            'order_id' => $order->id,
            'status'   => 'pending',
        ]);

        $this->assertEquals(2, \App\Models\OrderApproval::where('order_id', $order->id)->count());
    }

    #[Test]
    public function it_creates_order_without_workflow_steps()
    {
        // اگر workflow step نباشه، سفارش باید ثبت بشه بدون خطا
        $order = $this->service->createOrder($this->makeOrderData());

        $this->assertInstanceOf(Order::class, $order);
        $this->assertEquals(0, \App\Models\OrderApproval::where('order_id', $order->id)->count());
    }
}
