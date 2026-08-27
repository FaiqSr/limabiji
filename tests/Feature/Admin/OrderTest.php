<?php

namespace Tests\Feature\Admin;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $editor;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->editor = User::factory()->create(['role' => 'editor']);
    }

    public function test_guest_is_redirected_from_orders(): void
    {
        $response = $this->get(route('admin.orders.index'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_view_orders_index(): void
    {
        $order = Order::create([
            'order_number' => 'ORD-2026-TEST01',
            'customer_name' => 'John Coffee',
            'customer_email' => 'john@example.com',
            'customer_phone' => '081234567890',
            'shipping_address' => 'Jl. Sudirman No 45',
            'city' => 'Jakarta Selatan',
            'province' => 'DKI Jakarta',
            'subtotal' => 150000,
            'shipping_cost' => 18000,
            'total_amount' => 168000,
            'payment_status' => 'paid',
            'shipping_status' => 'unfulfilled',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.orders.index'));
        $response->assertStatus(200);
        $response->assertSee('ORD-2026-TEST01');
        $response->assertSee('John Coffee');
        $response->assertSee('Jakarta Selatan');
    }

    public function test_editor_can_view_order_details(): void
    {
        $product = Product::create([
            'name' => 'Flores Bajawa Premium',
            'slug' => 'flores-bajawa-premium',
            'category' => 'arabika',
            'roast_level' => 'medium',
            'base_price_200g' => 80000,
            'price_500g' => 180000,
            'price_1kg' => 340000,
            'stock' => 50,
            'is_active' => true,
        ]);

        $order = Order::create([
            'order_number' => 'ORD-2026-TEST02',
            'customer_name' => 'Siti Barista',
            'customer_email' => 'siti@example.com',
            'customer_phone' => '081987654321',
            'shipping_address' => 'Jl. Braga No 12',
            'city' => 'Bandung',
            'province' => 'Jawa Barat',
            'courier' => 'JNE REG',
            'subtotal' => 80000,
            'shipping_cost' => 12000,
            'total_amount' => 92000,
            'payment_status' => 'paid',
            'shipping_status' => 'unfulfilled',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'weight' => '200g',
            'grind_size' => 'whole_bean',
            'unit_price' => 80000,
            'quantity' => 1,
            'subtotal' => 80000,
        ]);

        $response = $this->actingAs($this->editor)->get(route('admin.orders.show', $order));
        $response->assertStatus(200);
        $response->assertSee('ORD-2026-TEST02');
        $response->assertSee('Siti Barista');
        $response->assertSee('Flores Bajawa Premium');
        $response->assertSee('Bandung');
    }

    public function test_can_update_shipping_status_and_tracking_number(): void
    {
        $order = Order::create([
            'order_number' => 'ORD-2026-TEST03',
            'customer_name' => 'Budi Roaster',
            'customer_email' => 'budi@example.com',
            'customer_phone' => '081122334455',
            'shipping_address' => 'Jl. Malioboro 10',
            'city' => 'Yogyakarta',
            'province' => 'DI Yogyakarta',
            'subtotal' => 100000,
            'shipping_cost' => 15000,
            'total_amount' => 115000,
            'payment_status' => 'paid',
            'shipping_status' => 'unfulfilled',
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.orders.update-shipping', $order), [
            'shipping_status' => 'shipped',
            'tracking_number' => 'JNE-SOC-987654321',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $fresh = $order->fresh();
        $this->assertEquals('shipped', $fresh->shipping_status);
        $this->assertEquals('JNE-SOC-987654321', $fresh->tracking_number);
        $this->assertNotNull($fresh->shipped_at);
    }

    public function test_can_update_payment_status(): void
    {
        $order = Order::create([
            'order_number' => 'ORD-2026-TEST04',
            'customer_name' => 'David Buyer',
            'customer_email' => 'david@example.com',
            'customer_phone' => '0811002233',
            'shipping_address' => 'Jl. Thamrin 88',
            'city' => 'Jakarta Pusat',
            'subtotal' => 200000,
            'shipping_cost' => 10000,
            'total_amount' => 210000,
            'payment_status' => 'pending',
            'shipping_status' => 'unfulfilled',
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.orders.update-payment-status', $order), [
            'payment_status' => 'paid',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $fresh = $order->fresh();
        $this->assertEquals('paid', $fresh->payment_status);
        $this->assertNotNull($fresh->paid_at);
    }
}
