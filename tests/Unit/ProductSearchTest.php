<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductSearchTest extends TestCase
{
    use RefreshDatabase;

    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = Category::create(['name' => 'Trucks', 'slug' => 'trucks']);
    }

    public function test_scope_search_finds_products_by_name(): void
    {
        Product::create([
            'name' => '2020 Ford F-150',
            'slug' => '2020-ford-f-150',
            'description' => 'A truck.',
            'price' => 45000,
            'quantity' => 1,
            'category_id' => $this->category->id,
        ]);

        $this->assertSame(1, Product::search('ford')->count());
    }

    public function test_scope_search_finds_products_by_engine_and_store(): void
    {
        Product::create([
            'name' => 'Chevy Silverado',
            'slug' => 'chevy-silverado',
            'description' => 'A truck.',
            'price' => 40000,
            'quantity' => 1,
            'category_id' => $this->category->id,
            'engine' => '6.2L V8',
            'store' => 'Houston',
        ]);

        Product::create([
            'name' => 'GMC Sierra',
            'slug' => 'gmc-sierra',
            'description' => 'A truck.',
            'price' => 43000,
            'quantity' => 1,
            'category_id' => $this->category->id,
            'engine' => '6.6L V8',
            'store' => 'Dallas',
        ]);

        $this->assertSame(1, Product::search('6.2L V8')->count());
        $this->assertSame(1, Product::search('Houston')->count());
        $this->assertSame(2, Product::search('V8')->count());
    }

    public function test_scope_search_is_partial_and_case_insensitive(): void
    {
        Product::create([
            'name' => 'Toyota Tundra',
            'slug' => 'toyota-tundra',
            'description' => '',
            'price' => 48000,
            'quantity' => 1,
            'category_id' => $this->category->id,
            'transmission' => 'Automatic',
            'gvw' => '6900 lbs',
            'ecm_miles' => '98500',
        ]);

        $this->assertSame(1, Product::search('automatic')->count());
        $this->assertSame(1, Product::search('automat')->count());
        $this->assertSame(1, Product::search('6900')->count());
        $this->assertSame(1, Product::search('9850')->count());
    }

    public function test_scope_searchable_falls_back_to_like_on_sqlite(): void
    {
        Product::create([
            'name' => 'RAM 2500',
            'slug' => 'ram-2500',
            'description' => '',
            'price' => 56000,
            'quantity' => 1,
            'category_id' => $this->category->id,
            'engine' => '6.7L Cummins',
            'store' => 'Phoenix',
        ]);

        $this->assertSame(1, Product::searchable('Cummins')->count());
        $this->assertSame(1, Product::searchable('Phoenix')->count());
    }

    public function test_scope_search_ignores_blank_terms(): void
    {
        Product::create([
            'name' => 'Toyota Tundra',
            'slug' => 'toyota-tundra',
            'description' => '',
            'price' => 48000,
            'quantity' => 1,
            'category_id' => $this->category->id,
        ]);

        $this->assertSame(1, Product::search('')->count());
        $this->assertSame(1, Product::searchable('  ')->count());
    }
}
