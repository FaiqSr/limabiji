<?php

namespace Tests\Feature\Store;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreCatalogTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_can_view_store_index_page(): void
    {
        $response = $this->get('/store');

        $response->assertStatus(200);
        $response->assertSee('STORE');
    }

    public function test_can_filter_products_by_category(): void
    {
        $response = $this->get('/store?category=arabika');

        $response->assertStatus(200);
        $response->assertViewHas('products');
    }

    public function test_can_search_products(): void
    {
        $response = $this->get('/store?q=Gayo');

        $response->assertStatus(200);
        $response->assertSee('Gayo');
    }

    public function test_can_sort_products_by_price(): void
    {
        $response = $this->get('/store?sort=price_asc');

        $response->assertStatus(200);
    }

    public function test_can_view_product_detail_page(): void
    {
        $product = Product::active()->first();
        $this->assertNotNull($product);

        $response = $this->get('/store/'.$product->slug);

        $response->assertStatus(200);
        $response->assertSee($product->name);
    }

    public function test_returns_404_for_non_existent_product(): void
    {
        $response = $this->get('/store/non-existent-product-slug');

        $response->assertStatus(404);
    }
}
