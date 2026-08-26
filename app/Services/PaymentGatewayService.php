<?php

namespace App\Services;

use App\Models\Order;
use Carbon\Carbon;
use Composer\CaBundle\CaBundle;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentGatewayService
{
    /**
     * Payment method catalog. Key = UI method id, value = Core API charge parameters.
     */
    public const PAYMENT_METHODS = [
        'qris' => ['payment_type' => 'qris', 'label_key' => 'store.pay_method_qris'],
        'bca_va' => ['payment_type' => 'bank_transfer', 'bank' => 'bca', 'label_key' => 'store.pay_method_bca_va'],
        'bni_va' => ['payment_type' => 'bank_transfer', 'bank' => 'bni', 'label_key' => 'store.pay_method_bni_va'],
        'bri_va' => ['payment_type' => 'bank_transfer', 'bank' => 'bri', 'label_key' => 'store.pay_method_bri_va'],
        'permata_va' => ['payment_type' => 'permata', 'label_key' => 'store.pay_method_permata_va'],
        'mandiri_va' => ['payment_type' => 'echannel', 'label_key' => 'store.pay_method_mandiri_va'],
        'gopay' => ['payment_type' => 'gopay', 'label_key' => 'store.pay_method_gopay'],
        'shopeepay' => ['payment_type' => 'shopeepay', 'label_key' => 'store.pay_method_shopeepay'],
        'dana' => ['payment_type' => 'dana', 'label_key' => 'store.pay_method_dana'],
    ];

    protected string $serverKey;

    protected string $clientKey;

    protected bool $isProduction;

    protected string $apiBaseUrl;

    protected string $snapUrl;

    public function __construct()
    {
        $this->serverKey = (string) config('services.midtrans.server_key', env('MIDTRANS_SERVER_KEY', ''));
        $this->clientKey = (string) config('services.midtrans.client_key', env('MIDTRANS_CLIENT_KEY', 'SB-Mid-client-sample-key'));
        $this->isProduction = (bool) config('services.midtrans.is_production', env('MIDTRANS_IS_PRODUCTION', false));
        $this->apiBaseUrl = $this->isProduction ? 'https://api.midtrans.com' : 'https://api.sandbox.midtrans.com';
        $this->snapUrl = $this->isProduction
            ? 'https://app.midtrans.com/snap/v1/transactions'
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';
    }

    public function getClientKey(): string
    {
        return $this->clientKey;
    }

    public function getSnapJsUrl(): string
    {
        return $this->isProduction
            ? 'https://app.midtrans.com/snap/snap.js'
            : 'https://app.sandbox.midtrans.com/snap/snap.js';
    }

    /**
     * Methods currently available on the checkout UI.
     */
    public function availableMethods(): array
    {
        $enabled = config('services.midtrans.methods') ?: array_keys(self::PAYMENT_METHODS);

        return array_filter(self::PAYMENT_METHODS, fn (string $key) => in_array($key, $enabled, true), ARRAY_FILTER_USE_KEY);
    }

    public function isValidMethod(string $methodKey): bool
    {
        return isset($this->availableMethods()[$methodKey]);
    }

    public function methodLabel(string $methodKey): string
    {
        $method = self::PAYMENT_METHODS[$methodKey] ?? null;

        return $method ? __($method['label_key']) : $methodKey;
    }

    public function isDevMode(): bool
    {
        return empty($this->serverKey) || str_starts_with($this->serverKey, 'dummy') || str_starts_with($this->serverKey, 'sample');
    }

    /**
     * CA bundle path used to verify Midtrans SSL certificates.
     * Falls back to Guzzle's default behaviour (which may fail on systems
     * without a configured openssl.cafile / curl.cainfo).
     */
    protected function verifyOption(): bool|string
    {
        if (class_exists(CaBundle::class)) {
            try {
                return CaBundle::getSystemCaRootBundlePath();
            } catch (\Throwable $e) {
                // fall through to default
            }
        }

        return true;
    }

    /**
     * Create a Core API charge for the given order and payment method.
     * Transitions the order from `creating_payment` to `pending` on success,
     * or to `failed` when the provider rejects the request.
     *
     * @return array|null Safe payment instructions, or null when the charge failed.
     */
    public function createPayment(Order $order, string $methodKey): ?array
    {
        $method = self::PAYMENT_METHODS[$methodKey] ?? null;
        if (! $method) {
            Log::warning("Midtrans createPayment: unknown method {$methodKey} for order {$order->order_number}");

            return null;
        }

        if ($this->isDevMode()) {
            return $this->createMockInstructions($order, $methodKey, $method);
        }

        $payload = $this->buildChargePayload($order, $method);

        try {
            $response = Http::withOptions(['verify' => $this->verifyOption()])
                ->withBasicAuth($this->serverKey, '')
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->post($this->apiBaseUrl.'/v2/charge', $payload);

            if ($response->successful()) {
                $data = $response->json();
                $instructions = $this->extractInstructions($data, $methodKey);

                $order->update([
                    'payment_status' => 'pending',
                    'payment_method_type' => $methodKey,
                    'midtrans_transaction_id' => $data['transaction_id'] ?? null,
                    'midtrans_status' => $data['transaction_status'] ?? 'pending',
                    'payment_instructions' => $instructions,
                    'expires_at' => isset($instructions['expiry_time']) ? Carbon::parse($instructions['expiry_time']) : null,
                ]);

                return $instructions;
            }

            Log::warning("Midtrans charge rejected for order {$order->order_number} (method {$methodKey}): HTTP {$response->status()} {$response->body()}");
            $this->markOrderFailed($order, $methodKey);
        } catch (\Throwable $e) {
            Log::error("Midtrans charge exception for order {$order->order_number}: {$e->getMessage()}");
            $this->markOrderFailed($order, $methodKey);
        }

        return null;
    }

    /**
     * Sync the order against Midtrans GET status API. Safe to call on page load / polling.
     */
    public function syncStatus(Order $order): ?array
    {
        if ($this->isDevMode()) {
            return null;
        }

        try {
            $response = Http::withOptions(['verify' => $this->verifyOption()])
                ->withBasicAuth($this->serverKey, '')
                ->withHeaders(['Accept' => 'application/json'])
                ->get($this->apiBaseUrl.'/v2/'.$order->order_number.'/status');

            if ($response->successful()) {
                $this->applyNotification($order, $response->json());

                return $response->json();
            }

            Log::warning("Midtrans getStatus failed for order {$order->order_number}: HTTP {$response->status()}");
        } catch (\Throwable $e) {
            Log::error("Midtrans getStatus exception for order {$order->order_number}: {$e->getMessage()}");
        }

        return null;
    }

    /**
     * Handle an HTTP Notification webhook. Returns true only when the payload
     * is authentic and has been durably accepted.
     */
    public function handleNotification(array $payload): bool
    {
        $orderNumber = $payload['order_id'] ?? null;
        if (! $orderNumber) {
            return false;
        }

        $order = Order::where('order_number', $orderNumber)->first();
        if (! $order) {
            Log::warning("Midtrans notification for unknown order {$orderNumber}");

            return false;
        }

        if (! $this->isDevMode()) {
            if (! $this->verifyNotificationSignature($payload, $orderNumber)) {
                Log::warning("Midtrans notification signature mismatch for order {$orderNumber}");

                return false;
            }
        }

        return $this->applyNotification($order, $payload);
    }

    /**
     * Verify the SHA-512 signature using the raw provider payload values.
     * The gross_amount is hashed exactly as received (string), never reformatted.
     */
    protected function verifyNotificationSignature(array $payload, string $orderNumber): bool
    {
        $signatureKey = (string) ($payload['signature_key'] ?? '');
        if ($signatureKey === '') {
            return false;
        }

        $statusCode = (string) ($payload['status_code'] ?? '');
        $grossAmount = (string) ($payload['gross_amount'] ?? '');

        $expected = hash('sha512', $orderNumber.$statusCode.$grossAmount.$this->serverKey);

        return hash_equals($expected, $signatureKey);
    }

    /**
     * Monotonic state transition. A late `pending`, `cancel` or `expire`
     * notification never overwrites `paid` / `refunded` orders.
     */
    protected function applyNotification(Order $order, array $payload): bool
    {
        $transactionStatus = (string) ($payload['transaction_status'] ?? '');
        $fraudStatus = (string) ($payload['fraud_status'] ?? '');
        $transactionId = $payload['transaction_id'] ?? null;

        $target = $this->mapTransactionStatus($transactionStatus, $fraudStatus);
        if ($target === null) {
            return false;
        }

        $current = $order->payment_status;
        $terminal = ['paid', 'expired', 'cancelled', 'failed', 'refunded'];

        if (in_array($current, $terminal, true) && $target !== $current) {
            // Only allowed terminal transition: paid -> refunded
            if (! ($current === 'paid' && $target === 'refunded')) {
                return true;
            }
        }

        $data = [
            'payment_status' => $target,
            'midtrans_status' => $transactionStatus,
            'midtrans_transaction_id' => $transactionId ?: $order->midtrans_transaction_id,
            'payment_reference' => $transactionId ?: $order->payment_reference,
        ];

        if ($target === 'paid' && ! $order->paid_at) {
            $data['paid_at'] = now();
        }

        $order->update($data);

        Log::info("Midtrans order {$order->order_number} transitioned to {$target} (transaction_status: {$transactionStatus}, fraud: {$fraudStatus})");

        return true;
    }

    protected function mapTransactionStatus(string $transactionStatus, string $fraudStatus): ?string
    {
        return match ($transactionStatus) {
            'settlement' => 'paid',
            'capture' => match ($fraudStatus) {
                'accept' => 'paid',
                'challenge' => 'pending',
                default => 'failed',
            },
            'pending' => 'pending',
            'deny' => 'failed',
            'cancel' => 'cancelled',
            'expire' => 'expired',
            'refund' => 'refunded',
            default => null,
        };
    }

    protected function markOrderFailed(Order $order, string $methodKey): void
    {
        $order->update([
            'payment_status' => 'failed',
            'payment_method_type' => $methodKey,
            'midtrans_status' => 'failed',
        ]);
    }

    protected function buildChargePayload(Order $order, array $method): array
    {
        $payload = [
            'payment_type' => $method['payment_type'],
            'transaction_details' => [
                'order_id' => $order->order_number,
                'gross_amount' => (int) $order->total_amount,
            ],
            'customer_details' => [
                'first_name' => $order->customer_name,
                'email' => $order->customer_email,
                'phone' => $order->customer_phone,
                'billing_address' => [
                    'first_name' => $order->customer_name,
                    'address' => $order->shipping_address,
                    'city' => $order->city,
                    'postal_code' => $order->postal_code ?? '16128',
                    'country_code' => 'IDN',
                ],
                'shipping_address' => [
                    'first_name' => $order->customer_name,
                    'address' => $order->shipping_address,
                    'city' => $order->city,
                    'postal_code' => $order->postal_code ?? '16128',
                    'country_code' => 'IDN',
                ],
            ],
            'item_details' => $this->buildItemDetails($order),
        ];

        if (isset($method['bank'])) {
            $payload['bank_transfer'] = ['bank' => $method['bank']];
        }

        if ($method['payment_type'] === 'echannel') {
            $payload['echannel'] = [
                'bill_info1' => 'Payment:',
                'bill_info2' => 'Lima Biji Online Store',
            ];
        }

        return $payload;
    }

    protected function buildItemDetails(Order $order): array
    {
        $items = [];

        foreach ($order->items as $index => $item) {
            $items[] = [
                'id' => (string) ($item->product_id ?: 'ITEM-'.$order->id.'-'.($index + 1)),
                'price' => (int) $item->unit_price,
                'quantity' => (int) $item->quantity,
                'name' => mb_substr($item->product_name.' ('.$item->weight.', '.$item->grind_size.')', 0, 50),
            ];
        }

        if ($order->shipping_cost > 0) {
            $items[] = [
                'id' => 'SHIPPING',
                'price' => (int) $order->shipping_cost,
                'quantity' => 1,
                'name' => 'Shipping Cost ('.$order->courier.')',
            ];
        }

        return $items;
    }

    /**
     * Reduce the provider response to the fields the custom UI needs.
     */
    protected function extractInstructions(array $data, string $methodKey): array
    {
        $instructions = [
            'payment_type' => $data['payment_type'] ?? null,
            'transaction_id' => $data['transaction_id'] ?? null,
            'expiry_time' => $data['expiry_time'] ?? null,
            'va_numbers' => $data['va_numbers'] ?? null,
            'permata_va_number' => $data['permata_va_number'] ?? null,
            'bill_key' => $data['bill_key'] ?? null,
            'biller_code' => $data['biller_code'] ?? null,
            'qr_string' => $data['qr_string'] ?? null,
            'actions' => $this->safeActions($data['actions'] ?? null),
        ];

        return array_filter($instructions, fn ($value) => $value !== null && $value !== []);
    }

    protected function safeActions(?array $actions): array
    {
        if (! is_array($actions)) {
            return [];
        }

        return array_map(
            fn (array $action) => [
                'name' => $action['name'] ?? null,
                'method' => $action['method'] ?? null,
                'url' => $action['url'] ?? null,
            ],
            array_filter($actions, fn ($action) => is_array($action))
        );
    }

    /**
     * Deterministic local instructions so the custom UI works without sandbox keys.
     */
    protected function createMockInstructions(Order $order, string $methodKey, array $method): array
    {
        $paymentType = $method['payment_type'];
        $instructions = [
            'payment_type' => $paymentType,
            'transaction_id' => 'MOCK-'.strtoupper(substr(md5($order->order_number.microtime(true)), 0, 12)),
            'expiry_time' => now()->addDay()->format('Y-m-d H:i:s'),
            'va_numbers' => null,
            'actions' => [],
        ];

        if (in_array($paymentType, ['bank_transfer', 'permata'], true)) {
            $bank = strtoupper($method['bank'] ?? 'PERMATA');
            $instructions['va_numbers'] = [[
                'bank' => $method['bank'] ?? 'permata',
                'va_number' => $bank.'-'.substr(preg_replace('/\D/', '', $order->customer_phone), 0, 8).'-'.rand(1000, 9999),
            ]];
        } elseif ($paymentType === 'echannel') {
            $instructions['bill_key'] = '70012-'.rand(100000000000, 999999999999);
            $instructions['biller_code'] = '70012';
        } else {
            $instructions['actions'][] = [
                'name' => 'generate-qr-code',
                'method' => 'GET',
                'url' => 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data='.urlencode('MOCK-QRIS-'.$order->order_number),
            ];
            if ($paymentType === 'gopay') {
                $instructions['actions'][] = [
                    'name' => 'deeplink-redirect',
                    'method' => 'GET',
                    'url' => 'https://example.com/mock-gopay-deeplink',
                ];
            }
        }

        $order->update([
            'payment_status' => 'pending',
            'payment_method_type' => $methodKey,
            'midtrans_transaction_id' => $instructions['transaction_id'],
            'midtrans_status' => 'pending',
            'payment_instructions' => $instructions,
            'expires_at' => now()->addDay(),
        ]);

        return $instructions;
    }

    public function markAsPaid(Order $order, string $reference = 'DEV-MANUAL-VERIFICATION'): void
    {
        $order->update([
            'payment_status' => 'paid',
            'payment_reference' => $reference,
            'paid_at' => now(),
        ]);
    }
}
