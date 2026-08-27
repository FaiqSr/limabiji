@extends('admin.layouts.app')
@section('title', 'Order #' . $order->order_number)
@section('page_title', 'Order Management & Fulfillment')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <!-- Sticky Top Action Bar -->
    <div class="mb-6 bg-white/95 backdrop-blur-md border border-slate-200 rounded-2xl p-4 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3 min-w-0">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary text-xs py-2 px-3 shrink-0" title="Back to Orders list">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span class="hidden sm:inline">Back</span>
            </a>

            <div class="min-w-0">
                <div class="flex items-center gap-2 flex-wrap">
                    <h3 class="text-sm font-bold text-slate-900 font-mono truncate">
                        Order #{{ $order->order_number }}
                    </h3>
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold font-mono uppercase {{ $order->getPaymentStatusBadgeClass() }} shrink-0">
                        {{ $order->getPaymentStatusLabel() }}
                    </span>
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold font-mono uppercase {{ $order->getShippingStatusBadgeClass() }} shrink-0">
                        {{ $order->getShippingStatusLabel() }}
                    </span>
                </div>
                <p class="text-[11px] text-slate-500 font-mono truncate">
                    Placed {{ $order->created_at->format('d M Y, H:i') }} &bull; {{ $order->customer_name }} ({{ $order->city }})
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2 shrink-0">
            <button onclick="window.print()" type="button" class="btn btn-secondary text-xs py-2 px-3.5 shadow-2xs">
                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                <span>Print Invoice</span>
            </button>
        </div>
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left 2 Cols: Items Breakdown & Delivery Info -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Ordered Items Card -->
            <div class="bg-white rounded-lg border border-slate-200 shadow-2xs overflow-hidden">
                <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                    <h4 class="text-xs font-bold font-mono text-slate-400 uppercase tracking-wider">Purchased Items ({{ $order->items->count() }})</h4>
                    <span class="text-xs font-mono text-slate-500">Total Weight: {{ number_format($order->weight_grams ?? 0) }}g</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="table-modern">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Grind & Weight</th>
                                <th class="text-right">Price</th>
                                <th class="text-center">Qty</th>
                                <th class="text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->items as $item)
                                <tr>
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-lg bg-slate-100 border border-slate-200 overflow-hidden shrink-0 flex items-center justify-center">
                                                @if ($item->product && $item->product->image)
                                                    <img src="{{ $item->product->image }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                                                @else
                                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                                    </svg>
                                                @endif
                                            </div>
                                            <div>
                                                <span class="font-semibold text-slate-900 text-xs block">{{ $item->product_name }}</span>
                                                @if ($item->product)
                                                    <span class="text-[10px] text-slate-400 font-mono uppercase">{{ $item->product->category }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-xs font-mono text-slate-700">
                                            <div>{{ $item->weight }}</div>
                                            <div class="text-[10px] text-slate-400">{{ ucfirst(str_replace('_', ' ', $item->grind_size)) }}</div>
                                        </div>
                                    </td>
                                    <td class="text-right font-mono text-xs text-slate-700">
                                        Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center font-mono text-xs font-semibold text-slate-900">
                                        &times; {{ $item->quantity }}
                                    </td>
                                    <td class="text-right font-mono font-bold text-xs text-slate-900">
                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Financial Summary Box -->
                <div class="p-4 bg-slate-50 border-t border-slate-200">
                    <div class="max-w-xs ml-auto space-y-2 text-xs font-mono">
                        <div class="flex justify-between text-slate-600">
                            <span>Subtotal:</span>
                            <span>{{ $order->getFormattedSubtotal() }}</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Shipping ({{ $order->courier }}):</span>
                            <span>{{ $order->getFormattedShipping() }}</span>
                        </div>
                        <div class="border-t border-slate-200 pt-2 flex justify-between font-bold text-sm text-slate-900">
                            <span>Total Paid:</span>
                            <span class="text-emerald-700">{{ $order->getFormattedTotal() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer & Shipping Destination Card -->
            <div class="card-modern space-y-4">
                <h4 class="text-xs font-bold font-mono text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-2">Customer & Delivery Information</h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                    <!-- Customer Details -->
                    <div class="space-y-1.5">
                        <span class="font-mono uppercase text-slate-400 font-bold block text-[10px]">Buyer Profile</span>
                        <div class="p-3 bg-slate-50 rounded-lg border border-slate-200/80 space-y-1">
                            <div class="font-bold text-slate-900 text-xs">{{ $order->customer_name }}</div>
                            <div class="text-slate-600 font-mono text-[11px]">{{ $order->customer_email }}</div>
                            <div class="text-slate-600 font-mono text-[11px]">{{ $order->customer_phone }}</div>
                        </div>
                    </div>

                    <!-- Destination Address -->
                    <div class="space-y-1.5">
                        <span class="font-mono uppercase text-slate-400 font-bold block text-[10px]">Shipping Destination</span>
                        <div class="p-3 bg-slate-50 rounded-lg border border-slate-200/80 space-y-1">
                            <p class="text-slate-800 whitespace-pre-line text-xs">{{ $order->shipping_address }}</p>
                            <p class="font-semibold text-slate-900 text-xs">{{ $order->city }}{{ $order->province ? ', ' . $order->province : '' }} {{ $order->postal_code }}</p>
                            <p class="text-slate-500 font-mono text-[10px]">Courier Service: {{ $order->courier }}</p>
                        </div>
                    </div>
                </div>

                @if ($order->notes)
                    <div class="pt-2">
                        <span class="font-mono uppercase text-slate-400 font-bold block text-[10px] mb-1">Customer Delivery Notes</span>
                        <p class="p-3 bg-amber-50/60 border border-amber-200 rounded-lg text-xs text-amber-900 italic">
                            &ldquo;{{ $order->notes }}&rdquo;
                        </p>
                    </div>
                @endif
            </div>

        </div>

        <!-- Right 1 Col: Operations (Shipping Fulfillment & Payment Tracking) -->
        <div class="space-y-6">

            <!-- Fulfillment & Resi Form Card -->
            <div class="card-modern space-y-4">
                <h4 class="text-xs font-bold font-mono text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-2">Fulfillment & Resi</h4>

                <form action="{{ route('admin.orders.update-shipping', $order) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="shipping_status">Shipping Status</label>
                        <select id="shipping_status" name="shipping_status" class="text-xs font-medium" required>
                            <option value="unfulfilled" {{ $order->shipping_status === 'unfulfilled' ? 'selected' : '' }}>Unfulfilled (Needs Packing)</option>
                            <option value="processing" {{ $order->shipping_status === 'processing' ? 'selected' : '' }}>Processing / Packed</option>
                            <option value="shipped" {{ $order->shipping_status === 'shipped' ? 'selected' : '' }}>Shipped / In Transit</option>
                            <option value="delivered" {{ $order->shipping_status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ $order->shipping_status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <div>
                        <label for="tracking_number">Airwaybill / Nomor Resi Kurir</label>
                        <input type="text" id="tracking_number" name="tracking_number" value="{{ old('tracking_number', $order->tracking_number) }}" placeholder="e.g. SOC1234567890" class="font-mono text-xs">
                        <p class="text-[11px] text-slate-400 mt-1">Input courier tracking code once parcel is dispatched.</p>
                    </div>

                    <div class="space-y-1.5 pt-2 text-[11px] font-mono text-slate-500 border-t border-slate-100">
                        @if ($order->shipped_at)
                            <div class="flex justify-between">
                                <span>Shipped At:</span>
                                <span class="font-medium text-slate-800">{{ $order->shipped_at->format('d M Y, H:i') }}</span>
                            </div>
                        @endif
                        @if ($order->delivered_at)
                            <div class="flex justify-between">
                                <span>Delivered At:</span>
                                <span class="font-medium text-slate-800">{{ $order->delivered_at->format('d M Y, H:i') }}</span>
                            </div>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary w-full py-2.5 text-xs font-semibold shadow-2xs cursor-pointer">
                        Update Shipping & Resi
                    </button>
                </form>
            </div>

            <!-- Payment & Midtrans Details Card -->
            <div class="card-modern space-y-4">
                <h4 class="text-xs font-bold font-mono text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-2">Payment Details</h4>

                <div class="space-y-2 text-xs font-mono">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Method:</span>
                        <span class="font-semibold text-slate-900 uppercase">{{ $order->payment_method_type ?? $order->payment_method }}</span>
                    </div>

                    @if ($order->getVaNumber())
                        <div class="flex justify-between">
                            <span class="text-slate-500">VA Number:</span>
                            <span class="font-bold text-emerald-600 select-all">{{ $order->getVaNumber() }}</span>
                        </div>
                    @endif

                    @if ($order->midtrans_transaction_id)
                        <div class="flex justify-between">
                            <span class="text-slate-500">Midtrans Tx ID:</span>
                            <span class="text-slate-700 select-all truncate max-w-[140px]" title="{{ $order->midtrans_transaction_id }}">{{ $order->midtrans_transaction_id }}</span>
                        </div>
                    @endif

                    @if ($order->paid_at)
                        <div class="flex justify-between">
                            <span class="text-slate-500">Paid At:</span>
                            <span class="font-medium text-emerald-700">{{ $order->paid_at->format('d M Y, H:i') }}</span>
                        </div>
                    @endif

                    @if ($order->expires_at)
                        <div class="flex justify-between">
                            <span class="text-slate-500">Expires At:</span>
                            <span class="text-slate-700">{{ $order->expires_at->format('d M Y, H:i') }}</span>
                        </div>
                    @endif
                </div>

                <!-- Manual Status Override for Operator -->
                <div class="pt-3 border-t border-slate-100">
                    <span class="text-[10px] font-bold text-slate-400 font-mono uppercase block mb-2">Manual Payment Override</span>
                    <form action="{{ route('admin.orders.update-payment-status', $order) }}" method="POST" class="flex gap-2">
                        @csrf
                        @method('PUT')
                        <select name="payment_status" class="text-xs py-1.5 flex-1">
                            <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Failed</option>
                            <option value="expired" {{ $order->payment_status === 'expired' ? 'selected' : '' }}>Expired</option>
                        </select>
                        <button type="submit" class="btn btn-secondary text-xs py-1.5 px-3 cursor-pointer">
                            Apply
                        </button>
                    </form>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
