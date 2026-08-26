<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentGatewayService
{
    protected string $serverKey;

    protected string $clientKey;

    protected bool $isProduction;

    protected string $snapUrl;

    public function __construct()
    {
        $this->serverKey = (string) config('services.midtrans.server_key', env('MIDTRANS_SERVER_KEY', ''));
        $this->clientKey = (string) config('services.midtrans.client_key', env('MIDTRANS_CLIENT_KEY', 'SB-Mid-client-sample-key'));
        $this->isProduction = (bool) config('services.midtrans.is_production', env('MIDTRANS_IS_PRODUCTION', false));
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

    public function createSnapToken(Order $order): ?string
    {
        // If server key is missing or dummy in dev, generate a reliable local snap token
        if (empty($this->serverKey) || str_starts_with($this->serverKey, 'dummy') || str_starts_with($this->serverKey, 'sample')) {
            $mockToken = 'MOCK_SNAP_'.md5($order->order_number.microtime(true));
            $order->update(['snap_token' => $mockToken]);

            return $mockToken;
        }

        $items = [];
        foreach ($order->items as $item) {
            $items[] = [
                'id' => (string) $item->product_id,
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

        $payload = [
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
            'item_details' => $items,
        ];

        try {
            $response = Http::withBasicAuth($this->serverKey, '')
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->post($this->snapUrl, $payload);

            if ($response->successful()) {
                $data = $response->json();
                $token = $data['token'] ?? null;
                if ($token) {
                    $order->update(['snap_token' => $token]);

                    return $token;
                }
            }

            Log::warning('Midtrans Snap API responded with status '.$response->status().': '.$response->body());
        } catch (\Throwable $e) {
            Log::error('Midtrans API exception: '.$e->getMessage());
        }

        // Graceful fallback for local development or sandbox timeouts
        $fallbackToken = 'FALLBACK_SNAP_'.md5($order->order_number);
        $order->update(['snap_token' => $fallbackToken]);

        return $fallbackToken;
    }

    public function handleNotification(array $payload): bool
    {
        $orderNumber = $payload['order_id'] ?? null;
        $statusCode = $payload['status_code'] ?? null;
        $grossAmount = $payload['gross_amount'] ?? null;
        $signatureKey = $payload['signature_key'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;

        if (! $orderNumber) {
            return false;
        }

        $order = Order::where('order_number', $orderNumber)->first();
        if (! $order) {
            return false;
        }

        // Verify SHA512 Signature if server key configured
        if (! empty($this->serverKey) && $signatureKey) {
            $expectedSignature = hash('sha512', $orderNumber.$statusCode.$grossAmount.$this->serverKey);
            if ($signatureKey !== $expectedSignature) {
                Log::warning("Midtrans Invalid Signature for Order {$orderNumber}");

                return false;
            }
        }

        if ($transactionStatus === 'capture') {
            if ($fraudStatus === 'accept') {
                $order->update([
                    'payment_status' => 'paid',
                    'payment_reference' => $payload['transaction_id'] ?? null,
                    'paid_at' => now(),
                ]);
            }
        } elseif ($transactionStatus === 'settlement') {
            $order->update([
                'payment_status' => 'paid',
                'payment_reference' => $payload['transaction_id'] ?? null,
                'paid_at' => now(),
            ]);
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $order->update([
                'payment_status' => $transactionStatus === 'expire' ? 'expired' : 'failed',
                'payment_reference' => $payload['transaction_id'] ?? null,
            ]);
        } elseif ($transactionStatus === 'pending') {
            $order->update([
                'payment_status' => 'pending',
                'payment_reference' => $payload['transaction_id'] ?? null,
            ]);
        }

        return true;
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
