<?php

namespace Tests\Feature\Api;

use App\Models\Role;
use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $role = Role::create(['name' => 'فروش']);
        $this->user = User::create([
            'full_name' => 'کاربر تست',
            'username'  => 'product_api_user',
            'password'  => bcrypt('password'),
            'role_id'   => $role->id,
            'status'    => 'active',
        ]);
    }

    #[Test]
    public function unauthenticated_user_cannot_access_products()
    {
        $this->getJson('/api/v1/products')->assertStatus(401);
    }

    #[Test]
    public function authenticated_user_can_get_product_list()
    {
        Product::create(['name' => 'بتن M25', 'base_price' => 400000, 'status' => 'active']);
        Product::create(['name' => 'بتن M30', 'base_price' => 500000, 'status' => 'active']);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/v1/products');

        $response->assertStatus(200)->assertJsonCount(2);
    }

    #[Test]
    public function inactive_products_are_excluded()
    {
        Product::create(['name' => 'فعال', 'base_price' => 100000, 'status' => 'active']);
        Product::create(['name' => 'غیرفعال', 'base_price' => 100000, 'status' => 'inactive']);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/v1/products');

        $response->assertStatus(200)->assertJsonCount(1);
    }

    #[Test]
    public function authenticated_user_can_search_products()
    {
        Product::create(['name' => 'بتن M25', 'base_price' => 400000, 'status' => 'active']);
        Product::create(['name' => 'بتن M30', 'base_price' => 500000, 'status' => 'active']);
        Product::create(['name' => 'ملات سیمان', 'base_price' => 200000, 'status' => 'active']);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/v1/products?search=بتن');

        $response->assertStatus(200)->assertJsonCount(2);
    }

    #[Test]
    public function authenticated_user_can_get_product_detail()
    {
        $product = Product::create(['name' => 'بتن M40', 'base_price' => 600000, 'status' => 'active']);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/v1/products/' . $product->id);

        $response->assertStatus(200)
            ->assertJsonStructure(['id', 'name', 'base_price', 'status']);
    }
}
