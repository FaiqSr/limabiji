@extends('admin.layouts.app')
@section('title', 'Orders')
@section('page_title', 'Store Orders & Fulfillment')

@section('content')
<div class="space-y-6">

    <!-- Header & KPIs Row -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="card-modern p-4">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block font-mono">Total Orders</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="font-mono text-2xl font-bold text-slate-900">{{ number_format($stats['total_orders']) }}</span>
                <span class="text-[10px] font-mono font-bold px-1.5 py-0.5 rounded bg-slate-100 text-slate-700 border border-slate-200 uppercase">LIFETIME</span>
            </div>
        </div>

        <div class="card-modern p-4">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block font-mono">Total Revenue</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="font-mono text-xl sm:text-2xl font-bold text-emerald-700">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</span>
                <span class="text-[10px] font-mono font-bold px-1.5 py-0.5 rounded bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase">PAID</span>
            </div>
        </div>

        <div class="card-modern p-4">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block font-mono">To Ship / Pack</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="font-mono text-2xl font-bold text-amber-700">{{ number_format($stats['unfulfilled_orders']) }}</span>
                @if ($stats['unfulfilled_orders'] > 0)
                    <span class="text-[10px] font-mono font-bold px-1.5 py-0.5 rounded bg-amber-50 text-amber-800 border border-amber-200 uppercase animate-pulse">ACTION NEEDED</span>
                @else
                    <span class="text-[10px] font-mono font-bold px-1.5 py-0.5 rounded bg-slate-100 text-slate-700 border border-slate-200 uppercase">CLEAR</span>
                @endif
            </div>
        </div>

        <div class="card-modern p-4">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block font-mono">Pending Payment</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="font-mono text-2xl font-bold text-slate-700">{{ number_format($stats['pending_payment']) }}</span>
                <span class="text-[10px] font-mono font-bold px-1.5 py-0.5 rounded bg-sky-50 text-sky-700 border border-sky-200 uppercase">AWAITING</span>
            </div>
        </div>
    </div>

    <!-- Search & Filter Controls -->
    <div class="card-modern p-3.5">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-2.5">
            <div class="lg:col-span-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by order #, customer, email, resi..." class="text-xs">
            </div>

            <div>
                <select name="payment_status" class="text-xs">
                    <option value="">All Payment Status</option>
                    <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Pending Payment</option>
                    <option value="failed" {{ request('payment_status') === 'failed' ? 'selected' : '' }}>Failed</option>
                    <option value="expired" {{ request('payment_status') === 'expired' ? 'selected' : '' }}>Expired</option>
                </select>
            </div>

            <div>
                <select name="shipping_status" class="text-xs">
                    <option value="">All Shipping Status</option>
                    <option value="unfulfilled" {{ request('shipping_status') === 'unfulfilled' ? 'selected' : '' }}>Unfulfilled</option>
                    <option value="processing" {{ request('shipping_status') === 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="shipped" {{ request('shipping_status') === 'shipped' ? 'selected' : '' }}>Shipped / In Transit</option>
                    <option value="delivered" {{ request('shipping_status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ request('shipping_status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="btn btn-secondary text-xs py-2 px-3.5 flex-1">
                    Filter
                </button>
                @if (request()->hasAny(['search', 'payment_status', 'shipping_status']))
                    <a href="{{ route('admin.orders.index') }}" class="btn text-xs py-2 px-3 text-slate-500 hover:text-slate-800 border border-slate-200" title="Reset filter">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-lg border border-slate-200 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Order & Date</th>
                        <th>Customer</th>
                        <th>Destination & Courier</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Shipping</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td>
                                <div>
                                    <a href="{{ route('admin.orders.show', $order) }}" class="font-mono font-bold text-xs text-slate-900 hover:text-emerald-700 transition-colors">
                                        #{{ $order->order_number }}
                                    </a>
                                    <p class="text-[10px] text-slate-400 font-mono mt-0.5">
                                        {{ $order->created_at->format('d M Y, H:i') }}
                                    </p>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <div class="font-semibold text-slate-900 text-xs">{{ $order->customer_name }}</div>
                                    <div class="text-[11px] text-slate-500 font-mono">{{ $order->customer_phone }}</div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <div class="text-xs text-slate-800 font-medium">{{ $order->city }}{{ $order->province ? ', ' . $order->province : '' }}</div>
                                    <div class="text-[10px] text-slate-500 font-mono flex items-center gap-1.5 mt-0.5">
                                        <span class="px-1.5 py-0.2 rounded bg-slate-100 text-slate-700 border border-slate-200 font-semibold">{{ $order->courier }}</span>
                                        @if ($order->tracking_number)
                                            <span class="text-emerald-600 font-semibold truncate max-w-[120px]" title="Resi: {{ $order->tracking_number }}">
                                                Resi: {{ $order->tracking_number }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="text-xs text-slate-700 font-mono">
                                    {{ $order->items->sum('quantity') }} items
                                </span>
                            </td>
                            <td>
                                <span class="font-mono font-bold text-xs text-slate-900">
                                    {{ $order->getFormattedTotal() }}
                                </span>
                            </td>
                            <td>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold uppercase font-mono {{ $order->getPaymentStatusBadgeClass() }}">
                                    {{ $order->getPaymentStatusLabel() }}
                                </span>
                            </td>
                            <td>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold uppercase font-mono {{ $order->getShippingStatusBadgeClass() }}">
                                    {{ $order->getShippingStatusLabel() }}
                                </span>
                            </td>
                            <td class="text-right">
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-secondary text-xs py-1.5 px-3">
                                    Manage &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-10 text-slate-500">
                                <div class="max-w-xs mx-auto text-center space-y-2">
                                    <svg class="w-10 h-10 text-slate-300 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                    </svg>
                                    <p class="text-xs font-semibold text-slate-700">No orders found</p>
                                    <p class="text-[11px] text-slate-400">Orders from buyers will appear here once placed.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($orders->hasPages())
            <div class="p-3.5 border-t border-slate-100">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
