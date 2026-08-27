<?php

namespace Tests\Feature\Store;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrackingTest extends TestCase
{
    use RefreshDatabase;

    protected function createOrder(array $overrides = []): Order
    {
        return Order::create(array_merge([
            'order_number' => 'LB-20260827-339C9',
            'customer_name' => 'Tracking Tester',
            'customer_email' => 'track@example.com',
            'customer_phone' => '081234567890',
            'shipping_address' => 'Jl. Tracking No. 7',
            'city' => 'Bogor',
            'subtotal' => 150000,
            'shipping_cost' => 24000,
            'total_amount' => 174000,
            'payment_method' => 'midtrans',
            'payment_method_type' => 'bca_va',
            'payment_status' => 'paid',
            'shipping_status' => 'shipped',
            'tracking_number' => 'JNE0123456789',
            'courier' => 'JNE REG',
        ], $overrides));
    }

    protected function addItem(Order $order, array $overrides = []): OrderItem
    {
        return OrderItem::create(array_merge([
            'order_id' => $order->id,
            'product_name' => 'Enzymatic Gayo',
            'weight' => '200g',
            'grind_size' => 'whole_bean',
            'unit_price' => 150000,
            'quantity' => 1,
            'subtotal' => 150000,
        ], $overrides));
    }

    public function test_tracking_page_loads_without_query(): void
    {
        $this->get(route('store.tracking'))
            ->assertOk()
            ->assertSee('Track Your Order')
            ->assertDontSee('Order Not Found');
    }

    public function test_tracking_found_order_shows_progress_and_details(): void
    {
        $order = $this->createOrder();
        $this->addItem($order);

        $this->get(route('store.tracking', ['order' => $order->order_number]))
            ->assertOk()
            ->assertSee($order->order_number)
            ->assertSee('Order Placed')
            ->assertSee('Payment Confirmed')
            ->assertSee('Packed & Processing')
            ->assertSee('Shipped')
            ->assertSee('Delivered')
            ->assertSee('JNE0123456789')
            ->assertSee('Enzymatic Gayo')
            ->assertSee('Rp 174.000')
            ->assertSee('Shipped');
    }

    public function test_tracking_lookup_is_case_insensitive(): void
    {
        $order = $this->createOrder();

        $this->get(route('store.tracking', ['order' => 'lb-20260827-339c9']))
            ->assertOk()
            ->assertSee($order->order_number);
    }

    public function test_tracking_unknown_order_shows_not_found(): void
    {
        $this->get(route('store.tracking', ['order' => 'LB-00000000-00000']))
            ->assertOk()
            ->assertSee('Order Not Found');
    }
}
