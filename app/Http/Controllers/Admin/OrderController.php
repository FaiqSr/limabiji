<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('items')->latest();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%")
                    ->orWhere('tracking_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->input('payment_status'));
        }

        if ($request->filled('shipping_status')) {
            $query->where('shipping_status', $request->input('shipping_status'));
        }

        $orders = $query->paginate(15)->withQueryString();

        $stats = [
            'total_orders' => Order::count(),
            'paid_orders' => Order::where('payment_status', 'paid')->count(),
            'pending_payment' => Order::where('payment_status', 'pending')->count(),
            'unfulfilled_orders' => Order::where('payment_status', 'paid')
                ->whereIn('shipping_status', ['unfulfilled', 'processing'])
                ->count(),
            'total_revenue' => Order::where('payment_status', 'paid')->sum('total_amount'),
        ];

        return view('admin.store.orders.index', compact('orders', 'stats'));
    }

    public function show(Order $order)
    {
        $order->load(['items.product']);

        $store = [
            'name' => SiteSetting::get('site_name', 'id', SiteSetting::get('site_name', 'en', 'Lima Biji Agritech')),
            'address' => SiteSetting::get('contact_address', 'id', 'Bogor, Jawa Barat, Indonesia'),
            'email' => SiteSetting::get('contact_email', null, 'export@limabijiagritech.com'),
            'phone' => SiteSetting::get('contact_phone', null, '+62 812 3456 7890'),
        ];

        return view('admin.store.orders.show', compact('order', 'store'));
    }

    public function updateShipping(Request $request, Order $order)
    {
        $validated = $request->validate([
            'shipping_status' => ['required', 'string', 'in:unfulfilled,processing,shipped,delivered,cancelled'],
            'tracking_number' => ['nullable', 'string', 'max:255'],
        ]);

        $updates = [
            'shipping_status' => $validated['shipping_status'],
            'tracking_number' => $validated['tracking_number'] ?? null,
        ];

        if ($validated['shipping_status'] === 'shipped' && ! $order->shipped_at) {
            $updates['shipped_at'] = now();
        } elseif ($validated['shipping_status'] === 'delivered' && ! $order->delivered_at) {
            $updates['delivered_at'] = now();
            if (! $order->shipped_at) {
                $updates['shipped_at'] = now();
            }
        }

        $order->update($updates);

        return back()->with('success', "Order #{$order->order_number} shipping status updated to ".ucfirst($validated['shipping_status']).'.');
    }

    public function updatePaymentStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'payment_status' => ['required', 'string', 'in:pending,paid,failed,expired'],
        ]);

        $updates = [
            'payment_status' => $validated['payment_status'],
        ];

        if ($validated['payment_status'] === 'paid' && ! $order->paid_at) {
            $updates['paid_at'] = now();
        }

        $order->update($updates);

        return back()->with('success', "Order #{$order->order_number} payment status updated to ".ucfirst($validated['payment_status']).'.');
    }
}
