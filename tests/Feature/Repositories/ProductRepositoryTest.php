<?php

namespace Tests\Feature\Repositories;

use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProductRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected ProductRepository $repo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repo = app(ProductRepository::class);
    }

    #[Test]
    public function it_creates_a_product()
    {
        $product = $this->repo->create([
            'name'       => 'بتن M30',
            'base_price' => 500000,
            'status'     => 'active',
        ]);

        $this->assertInstanceOf(Product::class, $product);
        $this->assertDatabaseHas('products', ['name' => 'بتن M30']);
    }

    #[Test]
    public function it_searches_products_by_name()
    {
        Product::create(['name' => 'بتن M25', 'base_price' => 400000, 'status' => 'active']);
        Product::create(['name' => 'بتن M30', 'base_price' => 500000, 'status' => 'active']);
        Product::create(['name' => 'ملات سیمان', 'base_price' => 200000, 'status' => 'active']);

        $results = $this->repo->search('بتن');

        $this->assertCount(2, $results);
    }

    #[Test]
    public function it_returns_only_active_products()
    {
        Product::create(['name' => 'محصول فعال', 'base_price' => 100000, 'status' => 'active']);
        Product::create(['name' => 'محصول غیرفعال', 'base_price' => 100000, 'status' => 'inactive']);

        $results = $this->repo->getActiveProducts();

        $this->assertCount(1, $results);
        $this->assertEquals('active', $results->first()->status);
    }

    #[Test]
    public function it_finds_product_by_id()
    {
        $product = Product::create(['name' => 'بتن M40', 'base_price' => 600000, 'status' => 'active']);

        $found = $this->repo->getProductWithRelations($product->id);

        $this->assertEquals($product->id, $found->id);
        $this->assertEquals('بتن M40', $found->name);
    }
}
