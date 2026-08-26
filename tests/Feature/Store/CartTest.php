<?php

namespace Tests\Feature\Store;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_can_fetch_empty_cart(): void
    {
        $response = $this->getJson('/cart');

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'item_count' => 0,
            'subtotal' => 0,
        ]);
    }

    public function test_can_add_product_to_cart_with_variants(): void
    {
        $product = Product::active()->first();

        $response = $this->postJson('/cart/add', [
            'product_id' => $product->id,
            'weight' => '500g',
            'grind_size' => 'fine',
            'quantity' => 2,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'item_count' => 2,
        ]);
        $this->assertEquals(2 * $product->price_500g, $response->json('subtotal'));
    }

    public function test_can_update_cart_quantity(): void
    {
        $product = Product::active()->first();

        $addResponse = $this->postJson('/cart/add', [
            'product_id' => $product->id,
            'weight' => '200g',
            'grind_size' => 'whole_bean',
            'quantity' => 1,
        ]);

        $cart = $addResponse->json('cart');
        $key = array_key_first($cart);

        $updateResponse = $this->postJson('/cart/update', [
            'key' => $key,
            'quantity' => 3,
        ]);

        $updateResponse->assertStatus(200);
        $updateResponse->assertJson([
            'status' => 'success',
            'item_count' => 3,
        ]);
    }

    public function test_can_remove_item_from_cart(): void
    {
        $product = Product::active()->first();

        $addResponse = $this->postJson('/cart/add', [
            'product_id' => $product->id,
            'weight' => '200g',
            'grind_size' => 'whole_bean',
            'quantity' => 1,
        ]);

        $cart = $addResponse->json('cart');
        $key = array_key_first($cart);

        $removeResponse = $this->postJson('/cart/remove', [
            'key' => $key,
        ]);

        $removeResponse->assertStatus(200);
        $removeResponse->assertJson([
            'status' => 'success',
            'item_count' => 0,
        ]);
    }
}
