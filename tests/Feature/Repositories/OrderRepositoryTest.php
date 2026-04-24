<?php

namespace Tests\Feature\Repositories;

use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\CustomerContact;
use App\Models\Company;
use App\Models\User;
use App\Models\Role;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Repositories\OrderRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class OrderRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected OrderRepository $repo;
    protected Customer $customer;
    protected Company $company;
    protected CustomerAddress $address;
    protected CustomerContact $contact;
    protected User $user;
    protected Role $role;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repo = app(OrderRepository::class);

        // ساخت پیش‌نیازهای مشترک
        $this->role = Role::create(['name' => 'فروش']);

        $this->user = User::create([
            'full_name' => 'کاربر تست',
            'username'  => 'test_user',
            'password'  => bcrypt('password'),
            'role_id'   => $this->role->id,
            'status'    => 'active',
        ]);

        $this->customer = Customer::create([
            'name'   => 'شرکت تست',
            'status' => 'active',
        ]);

        $this->company = Company::create([
            'name' => 'شرکت ما',
        ]);

        $this->address = CustomerAddress::create([
            'customer_id' => $this->customer->id,
            'title'       => 'دفتر مرکزی',
            'province'    => 'تهران',
            'city'        => 'تهران',
            'full_address' => 'آدرس تست',
            'is_default'  => true,
        ]);

        $this->contact = CustomerContact::create([
            'address_id' => $this->address->id,
            'full_name'  => 'علی تست',
            'mobile'     => '09120000000',
        ]);
    }

    private function makeOrder(array $override = []): Order
    {
        return Order::create(array_merge([
            'customer_id' => $this->customer->id,
            'company_id'  => $this->company->id,
            'address_id'  => $this->address->id,
            'contact_id'  => $this->contact->id,
            'is_official' => true,
            'status'      => 'pending',
            'created_by'  => $this->user->id,
        ], $override));
    }

    #[Test]
    public function it_creates_an_order()
    {
        $order = $this->makeOrder();

        $this->assertDatabaseHas('orders', [
            'customer_id' => $this->customer->id,
            'status'      => 'pending',
        ]);
        $this->assertInstanceOf(Order::class, $order);
    }

    #[Test]
    public function it_fetches_order_with_all_relations()
    {
        $order = $this->makeOrder();

        $product = Product::create([
            'name'       => 'محصول تست',
            'base_price' => 100000,
            'status'     => 'active',
        ]);

        OrderItem::create([
            'order_id'   => $order->id,
            'product_id' => $product->id,
            'quantity'   => 2,
            'base_price' => 100000,
            'final_price' => 200000,
        ]);

        $result = $this->repo->getOrderWithRelations($order->id);

        $this->assertNotNull($result->customer);
        $this->assertEquals(1, $result->items->count());
        $this->assertNotNull($result->company);
        $this->assertNotNull($result->address);
        $this->assertNotNull($result->contact);
    }

    #[Test]
    public function it_gets_pending_orders()
    {
        $this->makeOrder(['status' => 'pending']);
        $this->makeOrder(['status' => 'pending']);
        $this->makeOrder(['status' => 'approved']);

        $pending = $this->repo->getPendingOrders();

        $this->assertCount(2, $pending);
    }

    #[Test]
    public function it_updates_order_status()
    {
        $order = $this->makeOrder(['status' => 'pending']);

        $this->repo->update($order->id, ['status' => 'approved']);

        $this->assertDatabaseHas('orders', [
            'id'     => $order->id,
            'status' => 'approved',
        ]);
    }
}
