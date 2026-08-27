<?php

namespace Tests\Feature\Store;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        Http::fake([
            '*/calculate/district/domestic-cost' => Http::response([
                'meta' => ['message' => 'ok', 'code' => 200, 'status' => 'success'],
                'data' => [
                    ['name' => 'JNE', 'code' => 'jne', 'service' => 'REG', 'description' => 'Reguler', 'cost' => 18000, 'etd' => '2-3 days'],
                    ['name' => 'J&T', 'code' => 'jnt', 'service' => 'EZ', 'description' => 'Express', 'cost' => 20000, 'etd' => '1-2 days'],
                ],
            ]),
        ]);
    }

    public function test_checkout_redirects_when_cart_is_empty(): void
    {
        $response = $this->get('/checkout');

        $response->assertRedirect('/store');
    }

    public function test_can_view_checkout_page_with_items(): void
    {
        $product = Product::active()->first();

        $cartKey = md5("{$product->id}_200g_whole_bean");
        $cartData = [
            $cartKey => [
                'key' => $cartKey,
                'product_id' => $product->id,
                'name' => $product->name,
                'name_id' => $product->name_id,
                'slug' => $product->slug,
                'image' => $product->image,
                'weight' => '200g',
                'grind_size' => 'whole_bean',
                'unit_price' => $product->base_price_200g,
                'quantity' => 1,
                'subtotal' => $product->base_price_200g,
            ],
        ];

        $response = $this->withSession(['limabiji_cart' => $cartData])->get('/checkout');

        $response->assertStatus(200);
        $response->assertSee($product->name);
    }

    public function test_can_process_checkout_and_create_order(): void
    {
        $product = Product::active()->first();

        $cartKey = md5("{$product->id}_500g_medium");
        $cartData = [
            $cartKey => [
                'key' => $cartKey,
                'product_id' => $product->id,
                'name' => $product->name,
                'name_id' => $product->name_id,
                'slug' => $product->slug,
                'image' => $product->image,
                'weight' => '500g',
                'grind_size' => 'medium',
                'unit_price' => $product->price_500g,
                'quantity' => 2,
                'subtotal' => 2 * $product->price_500g,
            ],
        ];

        $response = $this->withSession(['limabiji_cart' => $cartData])->post('/checkout', [
            'customer_name' => 'Faiq Developer',
            'customer_email' => 'faiq@example.com',
            'customer_phone' => '081234567890',
            'shipping_address' => 'Jl. Pajajaran No. 10',
            'city' => 'Bogor',
            'province' => 'JAWA BARAT',
            'shipping_district_id' => 1376,
            'postal_code' => '16128',
            'courier' => 'JNE::REG',
            'payment_method' => 'qris',
            'notes' => 'Testing order note',
        ]);

        $order = Order::where('customer_email', 'faiq@example.com')->first();
        $this->assertNotNull($order);
        $this->assertEquals('pending', $order->payment_status);
        $this->assertEquals('qris', $order->payment_method_type);
        $this->assertNotEmpty($order->payment_instructions);
        $this->assertCount(1, $order->items);
        $this->assertEquals(1376, (int) $order->shipping_district_id);
        $this->assertEquals('JAWA BARAT', $order->province);
        $this->assertEquals(18000, (int) $order->shipping_cost);
        $this->assertEquals(1000, (int) $order->weight_grams);

        $response->assertRedirect('/order/status/'.$order->order_number);
    }

    public function test_checkout_rejects_invalid_payment_method(): void
    {
        $product = Product::active()->first();

        $cartKey = md5("{$product->id}_200g_whole_bean");
        $cartData = [
            $cartKey => [
                'key' => $cartKey,
                'product_id' => $product->id,
                'name' => $product->name,
                'name_id' => $product->name_id,
                'slug' => $product->slug,
                'image' => $product->image,
                'weight' => '200g',
                'grind_size' => 'whole_bean',
                'unit_price' => $product->base_price_200g,
                'quantity' => 1,
                'subtotal' => $product->base_price_200g,
            ],
        ];

        $response = $this->withSession(['limabiji_cart' => $cartData])->post('/checkout', [
            'customer_name' => 'Faiq Developer',
            'customer_email' => 'faiq@example.com',
            'customer_phone' => '081234567890',
            'shipping_address' => 'Jl. Pajajaran No. 10',
            'city' => 'Bogor',
            'province' => 'JAWA BARAT',
            'shipping_district_id' => 1376,
            'courier' => 'JNE::REG',
            'payment_method' => 'unknown_method',
        ]);

        $response->assertSessionHasErrors('payment_method');
        $this->assertEquals(0, Order::where('customer_email', 'faiq@example.com')->count());
    }

    public function test_can_simulate_payment_as_paid(): void
    {
        $order = Order::create([
            'order_number' => 'LB-TEST-12345',
            'customer_name' => 'John Doe',
            'customer_email' => 'john@example.com',
            'customer_phone' => '081234567890',
            'shipping_address' => 'Address Test',
            'city' => 'Jakarta',
            'subtotal' => 95000,
            'shipping_cost' => 25000,
            'total_amount' => 120000,
            'payment_method' => 'midtrans',
            'payment_status' => 'pending',
        ]);

        $response = $this->post('/order/simulate/'.$order->order_number, [
            'action' => 'pay',
        ]);

        $response->assertRedirect('/order/status/'.$order->order_number);
        $order->refresh();
        $this->assertEquals('paid', $order->payment_status);
        $this->assertNotNull($order->paid_at);
    }
}
