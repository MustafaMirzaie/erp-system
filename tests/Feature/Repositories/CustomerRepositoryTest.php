<?php

namespace Tests\Feature\Repositories;

use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Repositories\CustomerRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CustomerRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected CustomerRepository $repo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repo = app(CustomerRepository::class);
    }

    #[Test]
    public function it_creates_a_customer()
    {
        $data = [
            'name' => 'شرکت تست'
        ];

        $customer = $this->repo->create($data);

        $this->assertDatabaseHas('customers', ['name' => 'شرکت تست']);
        $this->assertInstanceOf(Customer::class, $customer);
    }

    #[Test]
    public function it_creates_address_for_customer()
    {
        $customer = Customer::factory()->create();

        // مستقیماً از مدل آدرس می‌سازیم
        $address = CustomerAddress::create([
            'customer_id' => $customer->id,
            'title'       => 'دفتر مرکزی',
            'province'    => 'تهران',
            'city'        => 'تهران',
            'full_address' => 'تهران، خیابان ...',
            'is_default'  => true,
        ]);

        $this->assertDatabaseHas('customer_addresses', [
            'customer_id' => $customer->id,
            'title'       => 'دفتر مرکزی',
        ]);
    }

    #[Test]
    public function it_creates_contact_for_address()
    {
        // Contact به Address تعلق دارد، نه مستقیم به Customer
        $address = CustomerAddress::factory()->create();

        $contact = \App\Models\CustomerContact::create([
            'address_id' => $address->id,
            'full_name'  => 'علی',
            'mobile'     => '09120000000',
        ]);

        $this->assertDatabaseHas('customer_contacts', [
            'address_id' => $address->id,
            'full_name'  => 'علی',
        ]);
    }

    #[Test]
    public function it_fetches_customer_with_relations()
    {
        // درست: Contact از طریق Address به Customer وصل است
        $customer = Customer::factory()
            ->has(
                CustomerAddress::factory()
                    ->hasContacts(2)  // CustomerContactFactory
                    ->count(2),
                'addresses'
            )
            ->create();

        $result = $this->repo->getCustomerWithRelations($customer->id);

        $this->assertEquals(2, $result->addresses->count());

        // contacts از طریق addresses قابل دسترسی است
        $totalContacts = $result->addresses->sum(fn($addr) => $addr->contacts->count());
        $this->assertEquals(4, $totalContacts); // 2 آدرس × 2 contact
    }
}
