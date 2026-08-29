@extends('admin.layouts.app')
@section('title', 'Order #' . $order->order_number)
@section('page_title', 'Order Management & Fulfillment')

@section('content')

    <!-- ==================== PRINT-ONLY INVOICE ==================== -->
    <div class="invoice-print-only hidden">
        <div class="invoice-doc">
            <!-- 1. Store Info (Top Left) -->
            <div class="invoice-header">
                <div class="invoice-store">
                    <div class="invoice-store-name">{{ $store['name'] }}</div>
                    <div class="invoice-store-line">{{ $store['address'] }}</div>
                    <div class="invoice-store-line">{{ $store['email'] }} &bull; {{ $store['phone'] }}</div>
                </div>
                <div class="invoice-title">&nbsp;</div>
            </div>

            <!-- 2. Document Title & Date -->
            <div class="invoice-head">
                <div class="invoice-title-big">INVOICE</div>
                <div class="invoice-meta">
                    <div class="invoice-meta-row">
                        <span class="invoice-meta-label">Date Issued</span>
                        <span class="invoice-meta-value">{{ $order->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="invoice-meta-row">
                        <span class="invoice-meta-label">Reference</span>
                        <span class="invoice-meta-value">{{ $order->order_number }}</span>
                    </div>
                </div>
            </div>

            <!-- 3. Invoice Information -->
            <div class="invoice-info-grid">
                <div>
                    <div class="invoice-section-label">Billed To</div>
                    <div class="invoice-billto">
                        <div class="invoice-billto-name">{{ $order->customer_name }}</div>
                        <div class="invoice-billto-line">{{ $order->customer_email }}</div>
                        <div class="invoice-billto-line">{{ $order->customer_phone }}</div>
                        <div class="invoice-billto-address">{{ $order->shipping_address }}</div>
                        <div class="invoice-billto-line">{{ $order->city }}{{ $order->province ? ', '.$order->province : '' }} {{ $order->postal_code }}</div>
                    </div>
                </div>
                <div class="invoice-info-right">
                    <div class="invoice-line">
                        <span class="invoice-line-label">Invoice No.</span>
                        <span class="invoice-line-value">{{ str_replace('LB-', 'INV-', $order->order_number) }}</span>
                    </div>
                    <div class="invoice-line">
                        <span class="invoice-line-label">Order No.</span>
                        <span class="invoice-line-value">{{ $order->order_number }}</span>
                    </div>
                    <div class="invoice-line">
                        <span class="invoice-line-label">Payment Method</span>
                        <span class="invoice-line-value">{{ $order->payment_method_type ?? $order->payment_method }}</span>
                    </div>
                    <div class="invoice-line">
                        <span class="invoice-line-label">Courier</span>
                        <span class="invoice-line-value">{{ $order->courier }}</span>
                    </div>
                </div>
            </div>

            <!-- 4. Purchased Items Table -->
            <div class="invoice-section-label">Purchased Items</div>
            <table class="invoice-table">
                <thead>
                    <tr>
                        <th class="invoice-col-no">No</th>
                        <th class="invoice-col-desc">Product / Description</th>
                        <th class="invoice-col-qty">Qty</th>
                        <th class="invoice-col-num">Unit Price</th>
                        <th class="invoice-col-num">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $item)
                        <tr>
                            <td class="invoice-col-no">{{ $loop->iteration }}</td>
                            <td class="invoice-col-desc">
                                {{ $item->product_name }}
                                @if ($item->weight || $item->grind_size)
                                    <div class="invoice-desc-sub">
                                        @if ($item->weight){{ $item->weight }}@endif
                                        @if ($item->grind_size) &bull; {{ ucfirst(str_replace('_', ' ', $item->grind_size)) }}@endif
                                    </div>
                                @endif
                            </td>
                            <td class="invoice-col-qty">{{ $item->quantity }}</td>
                            <td class="invoice-col-num">{{ 'Rp '.number_format($item->unit_price, 0, ',', '.') }}</td>
                            <td class="invoice-col-num">{{ 'Rp '.number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- 5. Payment Summary -->
            <div class="invoice-summary">
                <div class="invoice-summary-inner">
                    <div class="invoice-summary-row">
                        <span>Subtotal</span>
                        <span>{{ $order->getFormattedSubtotal() }}</span>
                    </div>
                    <div class="invoice-summary-row">
                        <span>Shipping</span>
                        <span>{{ $order->getFormattedShipping() }}</span>
                    </div>
                    <div class="invoice-summary-row invoice-summary-total">
                        <span>TOTAL PAYMENT</span>
                        <span>{{ $order->getFormattedTotal() }}</span>
                    </div>
                </div>
            </div>

            <div class="invoice-footer">
                <span>Thank you for your order.</span>
            </div>
        </div>
    </div>
    <!-- ==================== END PRINT-ONLY INVOICE ==================== -->

    <div class="max-w-6xl mx-auto space-y-6 print:hidden">

    <!-- Sticky Top Action Bar -->
    <div  class="mb-6 bg-white/95 backdrop-blur-md border border-slate-200 rounded-2xl p-4 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
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

@push('print-styles')
<style>
    /* Sembunyikan invoice di tampilan layar desktop biasa */
    .invoice-print-only {
        display: none !important;
    }

    @media print {
        @page {
            size: A4;
            margin: 0;
        }

        /* 1. Print-isolasi: sembunyikan SEMUA konten halaman, tampilkan hanya invoice.
           Menggunakan visibility (bukan display) agar ukuran/layout invoice & multi-page
           tetap normal, sekaligus menjamin tidak ada elemen admin lain yang ikut tercetak. */
        body * {
            visibility: hidden !important;
        }
        .invoice-print-only,
        .invoice-print-only * {
            visibility: visible !important;
        }

        /* 2. Tempatkan invoice di puncak halaman dengan lebar penuh */
        html,
        body {
            margin: 0 !important;
            padding: 0 !important;
            background: #fff !important;
        }
        .invoice-print-only {
            display: block !important;
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 12mm !important;
            box-sizing: border-box !important;
            color: #0f172a !important;
            font-family: Georgia, 'Times New Roman', serif !important;
        }

        /* --- Sisa Style Desain Invoice Anda --- */
        .invoice-doc { max-width: 100%; }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #079f81;
            padding-bottom: 14px;
            margin-bottom: 22px;
        }
        .invoice-store-name { font-size: 20px; font-weight: 700; letter-spacing: -.3px; }
        .invoice-store-line { font-size: 11px; color: #475569; margin-top: 2px; }

        .invoice-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 22px;
        }
        .invoice-title-big { font-size: 34px; font-weight: 800; letter-spacing: 4px; color: #079f81; }

        .invoice-meta { text-align: right; }
        .invoice-meta-row { display: flex; gap: 18px; font-size: 11px; padding: 3px 0; }
        .invoice-meta-label { color: #64748b; text-align: right; min-width: 90px; }
        .invoice-meta-value { font-weight: 700; color: #0f172a; }

        .invoice-info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 28px;
            margin-bottom: 26px;
        }
        .invoice-info-right { align-self: end; }

        .invoice-section-label {
            font-size: 10px; text-transform: uppercase; letter-spacing: 1.5px;
            font-weight: 700; color: #64748b; margin-bottom: 8px;
            border-bottom: 1px solid #e2e8f0; padding-bottom: 5px;
        }

        .invoice-billto-name { font-weight: 700; font-size: 13px; }
        .invoice-billto-line { font-size: 11px; color: #334155; margin-top: 2px; }
        .invoice-billto-address { font-size: 11px; color: #334155; margin-top: 6px; white-space: pre-line; }

        .invoice-line { display: flex; justify-content: space-between; font-size: 11px; padding: 4px 0; }
        .invoice-line-label { color: #64748b; }
        .invoice-line-value { font-weight: 700; color: #0f172a; }

        .invoice-table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        .invoice-table th {
            background: #079f81 !important;
            color: #fff !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            font-size: 10px; text-transform: uppercase; letter-spacing: 1px;
            font-weight: 700; padding: 8px 10px; text-align: left;
        }
        .invoice-table td { font-size: 11px; padding: 9px 10px; border-bottom: 1px solid #e2e8f0; color: #1e293b; }
        .invoice-table tbody tr:nth-child(even) { background: #f8fafc !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }

        .invoice-col-no { width: 6%; }
        .invoice-col-desc { width: 52%; }
        .invoice-col-qty { width: 10%; text-align: center; }
        .invoice-col-num { text-align: right; }

        .invoice-desc-sub { font-size: 10px; color: #64748b; margin-top: 2px; }

        .invoice-summary { display: flex; justify-content: flex-end; margin-bottom: 26px; }
        .invoice-summary-inner { width: 280px; }
        .invoice-summary-row { display: flex; justify-content: space-between; font-size: 12px; padding: 5px 0; color: #334155; }
        .invoice-summary-total {
            border-top: 2px solid #079f81; margin-top: 6px; padding-top: 10px;
            font-size: 14px; font-weight: 800; color: #0f172a;
        }

        .invoice-footer {
            border-top: 1px solid #e2e8f0; padding-top: 12px; font-size: 10px;
            color: #94a3b8; text-align: center;
        }
    }


</style>
@endpush

