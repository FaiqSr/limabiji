<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CartService;
use App\Services\PaymentGatewayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function index(CartService $cartService): View|RedirectResponse
    {
        $cart = $cartService->getCart();
        if (empty($cart)) {
            return redirect()->route('store.index')->with('warning', __('store.checkout_empty_cart'));
        }

        $itemCount = $cartService->getItemCount();
        $subtotal = $cartService->getSubtotal();
        $defaultCourier = 'JNE REG';
        $defaultShippingCost = 25000;
        $total = $subtotal + $defaultShippingCost;

        return view('landingpages.store.checkout', compact('cart', 'itemCount', 'subtotal', 'defaultCourier', 'defaultShippingCost', 'total'));
    }

    public function process(
        Request $request,
        CartService $cartService,
        PaymentGatewayService $paymentService
    ): RedirectResponse|JsonResponse {
        $cart = $cartService->getCart();
        if (empty($cart)) {
            if ($request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => __('store.checkout_empty_cart')], 422);
            }

            return redirect()->route('store.index')->with('warning', __('store.checkout_empty_cart'));
        }

        $validated = $request->validate([
            'customer_name' => 'required|string|max:120',
            'customer_email' => 'required|email|max:150',
            'customer_phone' => 'required|string|max:30',
            'shipping_address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:10',
            'courier' => 'required|string|in:JNE REG,J&T Express,SiCepat BEST,GoSend / Grab Instant',
            'notes' => 'nullable|string|max:500',
        ]);

        $subtotal = $cartService->getSubtotal();

        $shippingCosts = [
            'JNE REG' => 25000,
            'J&T Express' => 28000,
            'SiCepat BEST' => 35000,
            'GoSend / Grab Instant' => 45000,
        ];
        $shippingCost = $shippingCosts[$validated['courier']] ?? 25000;
        $totalAmount = $subtotal + $shippingCost;

        $orderNumber = 'LB-'.date('Ymd').'-'.strtoupper(substr(uniqid(), -5));

        $order = DB::transaction(function () use ($validated, $orderNumber, $subtotal, $shippingCost, $totalAmount, $cart) {
            $order = Order::create([
                'order_number' => $orderNumber,
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'],
                'shipping_address' => $validated['shipping_address'],
                'city' => $validated['city'],
                'postal_code' => $validated['postal_code'] ?? null,
                'courier' => $validated['courier'],
                'notes' => $validated['notes'] ?? null,
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'total_amount' => $totalAmount,
                'payment_method' => 'midtrans',
                'payment_status' => 'pending',
            ]);

            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'] ?? null,
                    'product_name' => $item['name'] ?? 'Specialty Coffee',
                    'weight' => $item['weight'] ?? '200g',
                    'grind_size' => $item['grind_size'] ?? 'whole_bean',
                    'unit_price' => (int) ($item['unit_price'] ?? 0),
                    'quantity' => (int) ($item['quantity'] ?? 1),
                    'subtotal' => (int) ($item['subtotal'] ?? 0),
                ]);
            }

            return $order;
        });

        // Request Midtrans Snap Token
        $snapToken = $paymentService->createSnapToken($order);

        // Clear cart
        $cartService->clear();

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'order_number' => $order->order_number,
                'snap_token' => $snapToken,
                'redirect_url' => route('store.order.status', $order->order_number),
            ]);
        }

        return redirect()->route('store.order.status', $order->order_number);
    }

    public function status(string $orderNumber, PaymentGatewayService $paymentService): View
    {
        $order = Order::with('items')->where('order_number', $orderNumber)->firstOrFail();
        $clientKey = $paymentService->getClientKey();
        $snapJsUrl = $paymentService->getSnapJsUrl();

        return view('landingpages.store.order-status', compact('order', 'clientKey', 'snapJsUrl'));
    }

    public function simulatePayment(
        Request $request,
        string $orderNumber,
        PaymentGatewayService $paymentService
    ): RedirectResponse {
        $order = Order::where('order_number', $orderNumber)->firstOrFail();
        $action = $request->input('action', 'pay');

        if ($action === 'pay') {
            $paymentService->markAsPaid($order, 'SIMULATOR-'.strtoupper(substr(md5((string) time()), 0, 10)));

            return redirect()->route('store.order.status', $order->order_number)
                ->with('success', __('store.payment_simulated_success'));
        }

        $order->update(['payment_status' => 'failed']);

        return redirect()->route('store.order.status', $order->order_number)
            ->with('error', __('store.payment_simulated_failed'));
    }

    public function notification(Request $request, PaymentGatewayService $paymentService): JsonResponse
    {
        $payload = $request->all();
        $handled = $paymentService->handleNotification($payload);

        return response()->json(['status' => $handled ? 'ok' : 'error']);
    }
}
