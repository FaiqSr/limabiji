<?php

namespace Tests\Feature\Store;

use App\Models\Order;
use App\Services\PaymentGatewayService;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class MidtransPaymentTest extends TestCase
{
    use RefreshDatabase;

    protected string $serverKey = 'SB-Mid-server-testkey-123abc';

    protected function setUp(): void
    {
        parent::setUp();
        Config::set('services.midtrans.server_key', $this->serverKey);
        Config::set('services.midtrans.is_production', false);
    }

    protected function createOrder(array $overrides = []): Order
    {
        return Order::create(array_merge([
            'order_number' => 'LB-PAYTEST-'.strtoupper(substr(md5((string) time()), 0, 8)),
            'status_token' => 'poll-secret-token-'.substr(md5((string) time()), 0, 8),
            'customer_name' => 'Payment Tester',
            'customer_email' => 'pay@example.com',
            'customer_phone' => '081234567890',
            'shipping_address' => 'Jl. Testing No. 1',
            'city' => 'Jakarta',
            'subtotal' => 100000,
            'shipping_cost' => 25000,
            'total_amount' => 125000,
            'payment_method' => 'midtrans',
            'payment_status' => 'creating_payment',
        ], $overrides));
    }

    public function test_charge_creates_payment_and_stores_instructions(): void
    {
        Http::fake([
            'api.sandbox.midtrans.com/*' => Http::response([
                'status_code' => '201',
                'status_message' => 'QRIS transaction is created',
                'transaction_id' => 'qris-1111',
                'order_id' => 'LB-ORD',
                'gross_amount' => '125000.00',
                'payment_type' => 'qris',
                'transaction_status' => 'pending',
                'fraud_status' => 'accept',
                'actions' => [
                    ['name' => 'generate-qr-code', 'method' => 'GET', 'url' => 'https://api.sandbox.midtrans.com/v2/qris/qris-1111/qr-code'],
                ],
            ], 201),
        ]);

        $order = $this->createOrder();

        // Directly exercise the service via the notification-style flow is not enough;
        // hit the charge through a helper path instead.
        $service = app(PaymentGatewayService::class);
        $instructions = $service->createPayment($order, 'qris');

        $this->assertNotNull($instructions);
        $order->refresh();
        $this->assertEquals('pending', $order->payment_status);
        $this->assertEquals('qris', $order->payment_method_type);
        $this->assertEquals('qris-1111', $order->midtrans_transaction_id);
        $this->assertEquals('qris', $order->payment_instructions['payment_type']);

        Http::assertSent(fn ($request) => str_contains($request->url(), '/v2/charge'));
    }

    public function test_charge_failure_marks_order_failed(): void
    {
        Http::fake([
            'api.sandbox.midtrans.com/*' => Http::response([
                'status_code' => '400',
                'status_message' => 'Failed, invalid transaction',
            ], 400),
        ]);

        $order = $this->createOrder();
        $service = app(PaymentGatewayService::class);
        $instructions = $service->createPayment($order, 'qris');

        $this->assertNull($instructions);
        $order->refresh();
        $this->assertEquals('failed', $order->payment_status);
    }

    public function test_notification_with_valid_signature_marks_order_paid(): void
    {
        $order = $this->createOrder(['payment_status' => 'pending']);

        $statusCode = '200';
        $grossAmount = '125000.00';
        $signature = hash('sha512', $order->order_number.$statusCode.$grossAmount.$this->serverKey);

        $response = $this->post('/payment/midtrans/notification', [
            'order_id' => $order->order_number,
            'status_code' => $statusCode,
            'gross_amount' => $grossAmount,
            'transaction_status' => 'settlement',
            'transaction_id' => 'settle-9999',
            'signature_key' => $signature,
        ]);

        $response->assertJson(['status' => 'ok']);
        $order->refresh();
        $this->assertEquals('paid', $order->payment_status);
        $this->assertEquals('settle-9999', $order->payment_reference);
        $this->assertNotNull($order->paid_at);
    }

    public function test_notification_with_invalid_signature_is_rejected(): void
    {
        $order = $this->createOrder(['payment_status' => 'pending']);

        $response = $this->post('/payment/midtrans/notification', [
            'order_id' => $order->order_number,
            'status_code' => '200',
            'gross_amount' => '125000.00',
            'transaction_status' => 'settlement',
            'transaction_id' => 'settle-evil',
            'signature_key' => 'invalid-signature',
        ]);

        $response->assertJson(['status' => 'error']);
        $order->refresh();
        $this->assertEquals('pending', $order->payment_status);
    }

    public function test_late_pending_notification_does_not_downgrade_paid_order(): void
    {
        $order = $this->createOrder(['payment_status' => 'paid', 'paid_at' => now()]);

        $statusCode = '200';
        $grossAmount = '125000.00';
        $signature = hash('sha512', $order->order_number.$statusCode.$grossAmount.$this->serverKey);

        $response = $this->post('/payment/midtrans/notification', [
            'order_id' => $order->order_number,
            'status_code' => $statusCode,
            'gross_amount' => $grossAmount,
            'transaction_status' => 'pending',
            'signature_key' => $signature,
        ]);

        $response->assertJson(['status' => 'ok']);
        $order->refresh();
        $this->assertEquals('paid', $order->payment_status);
    }

    public function test_settlement_notification_is_idempotent(): void
    {
        $order = $this->createOrder(['payment_status' => 'paid', 'paid_at' => now()]);

        $statusCode = '200';
        $grossAmount = '125000.00';
        $signature = hash('sha512', $order->order_number.$statusCode.$grossAmount.$this->serverKey);
        $payload = [
            'order_id' => $order->order_number,
            'status_code' => $statusCode,
            'gross_amount' => $grossAmount,
            'transaction_status' => 'settlement',
            'signature_key' => $signature,
        ];

        $this->post('/payment/midtrans/notification', $payload)->assertJson(['status' => 'ok']);
        $this->post('/payment/midtrans/notification', $payload)->assertJson(['status' => 'ok']);

        $order->refresh();
        $this->assertEquals('paid', $order->payment_status);
        $this->assertEquals(1, Order::where('order_number', $order->order_number)->count());
    }

    public function test_status_polling_syncs_order_from_midtrans(): void
    {
        $order = $this->createOrder(['payment_status' => 'pending']);

        Http::fake([
            'api.sandbox.midtrans.com/*' => Http::response([
                'status_code' => '200',
                'transaction_status' => 'settlement',
                'transaction_id' => 'sync-5555',
                'payment_type' => 'qris',
                'gross_amount' => '125000.00',
                'fraud_status' => 'accept',
            ], 200),
        ]);

        $response = $this->get(route('store.order.payment-status', [$order->order_number, 'token' => $order->status_token]));

        $response->assertJson([
            'payment_status' => 'paid',
            'midtrans_status' => 'settlement',
        ]);

        $order->refresh();
        $this->assertEquals('paid', $order->payment_status);
        $this->assertEquals('sync-5555', $order->payment_reference);
    }

    public function test_status_polling_requires_matching_token(): void
    {
        $order = $this->createOrder(['payment_status' => 'pending']);

        $this->get(route('store.order.payment-status', $order->order_number))
            ->assertStatus(404);

        $this->get(route('store.order.payment-status', [$order->order_number, 'token' => 'wrong-token']))
            ->assertStatus(404);

        $order->refresh();
        $this->assertEquals('pending', $order->payment_status);
    }

    public function test_webhook_route_is_exempt_from_csrf(): void
    {
        $reflection = new \ReflectionClass(PreventRequestForgery::class);
        $property = $reflection->getProperty('neverVerify');
        $property->setAccessible(true);

        $exempt = $property->getValue();

        $this->assertContains('payment/midtrans/notification', $exempt, 'Midtrans webhook route must be CSRF-exempt for server-to-server notifications.');
    }

    public function test_notification_without_signature_is_rejected_in_production_with_blank_key(): void
    {
        Config::set('services.midtrans.server_key', '');
        app()->detectEnvironment(fn () => 'production');
        $this->assertTrue(app()->environment('production'));

        $order = $this->createOrder(['payment_status' => 'pending']);

        $response = $this->post('/payment/midtrans/notification', [
            'order_id' => $order->order_number,
            'status_code' => '200',
            'gross_amount' => '125000.00',
            'transaction_status' => 'settlement',
            'transaction_id' => 'no-signature-payload',
        ]);

        $response->assertJson(['status' => 'error']);
        $order->refresh();
        $this->assertEquals('pending', $order->payment_status);
    }

    public function test_notification_signature_is_skipped_in_testing_dev_mode(): void
    {
        Config::set('services.midtrans.server_key', '');
        $this->assertTrue(app()->environment('testing'));

        $order = $this->createOrder(['payment_status' => 'pending']);

        $response = $this->post('/payment/midtrans/notification', [
            'order_id' => $order->order_number,
            'status_code' => '200',
            'gross_amount' => '125000.00',
            'transaction_status' => 'settlement',
            'transaction_id' => 'dev-no-signature',
        ]);

        $response->assertJson(['status' => 'ok']);
        $order->refresh();
        $this->assertEquals('paid', $order->payment_status);
    }
}
