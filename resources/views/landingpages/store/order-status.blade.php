{{-- resources/views/landingpages/store/order-status.blade.php --}}
@extends('layouts.store')

@php
    $locale = app()->getLocale();

    $isPaid = $order->payment_status === 'paid';
    $isPending = $order->payment_status === 'pending';
    $isExpired = $order->payment_status === 'expired';
    $isFailed = in_array($order->payment_status, ['failed', 'expired'], true);

    $toneIcon = $isPaid
        ? 'bg-emerald-50 text-emerald-600 border-emerald-200'
        : ($isPending
            ? 'bg-amber-50 text-amber-600 border-amber-200'
            : 'bg-rose-50 text-rose-600 border-rose-200');
    $tonePill = $isPaid
        ? 'bg-emerald-100 text-emerald-800'
        : ($isPending
            ? 'bg-amber-100 text-amber-800'
            : 'bg-rose-100 text-rose-800');
    $toneBar = $isPaid
        ? 'bg-emerald-500'
        : ($isPending
            ? 'bg-amber-500'
            : 'bg-rose-500');

    $statusLabel = match ($order->payment_status) {
        'paid' => __('store.order_status_paid'),
        'pending' => __('store.order_status_pending'),
        'expired' => __('store.order_status_expired'),
        default => __('store.order_status_failed'),
    };

    $methodType = $order->payment_method_type;
    $vaNumber = $order->getVaNumber();
    $qrUrl = $order->getQrUrl();
    $deepLink = $order->getDeepLinkUrl();
@endphp

@push('title', __('store.order_status_title') . ' #' . $order->order_number . ': Lima Biji Agritech')

@push('meta')
    <meta name="robots" content="noindex, nofollow">
@endpush

@push('scripts')
    @if ($isPending && !empty($order->payment_instructions))
        @include('landingpages.store.partials.payment-polling', ['order' => $order])
    @endif
@endpush

@section('content')
<div class="bg-[#fafaf9] text-slate-800 min-h-[100dvh]">
    <div class="container mx-auto px-5 lg:px-10 py-10 lg:py-16 max-w-6xl">

        {{-- Flash Alerts --}}
        @if (session('success'))
            <div class="mb-8 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center gap-3 shadow-xs">
                <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                </span>
                <span class="text-xs sm:text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-8 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 flex items-center gap-3 shadow-xs">
                <span class="w-5 h-5 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </span>
                <span class="text-xs sm:text-sm font-medium">{{ session('error') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 items-start">

            {{-- LEFT: status masthead + payment focus --}}
            <div class="lg:col-span-7 space-y-8 min-w-0">

                {{-- Masthead --}}
                <header class="relative animate-rise" style="animation-delay: 0ms">
                    <div class="absolute left-0 top-1 bottom-1 w-1 rounded-full {{ $toneBar }}"></div>
                    <div class="pl-6 sm:pl-7">
                        <div class="flex items-center gap-3">
                            <span class="w-11 h-11 rounded-xl flex items-center justify-center border {{ $toneIcon }}">
                                @if ($isPaid)
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                @elseif ($isPending)
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                @else
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                @endif
                            </span>
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[11px] font-mono font-bold uppercase tracking-wider {{ $tonePill }}">
                                @if ($isPending)
                                    <span class="w-1.5 h-1.5 rounded-full bg-current animate-pulse-dot"></span>
                                @endif
                                {{ $statusLabel }}
                            </span>
                        </div>

                        <p class="mt-6 text-[11px] font-mono font-bold uppercase tracking-widest text-slate-400">{{ __('store.order_number') }}</p>
                        <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl text-slate-900 uppercase leading-none tracking-tight mt-1">
                            {{ $order->order_number }}
                        </h1>

                        <div class="mt-3 flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-500">
                            <span class="font-mono">{{ __('store.order_date') }}: {{ $order->created_at->format('d F Y, H:i') }} WIB</span>
                            @if ($order->courier)
                                <span class="text-slate-300">/</span>
                                <span class="font-mono">{{ __('store.order_courier') }}: {{ $order->courier }}</span>
                            @endif
                        </div>

                        @if ($isPending)
                            <div class="mt-7">
                                <button type="button" id="btn-pay-now"
                                    class="inline-flex items-center gap-2 px-8 py-3.5 rounded-xl bg-primary hover:bg-primary-hover active:scale-[0.98] text-white font-semibold text-xs sm:text-sm uppercase tracking-wider transition-all duration-200 shadow-sm cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    <span>{{ __('store.order_pay_now_btn') }}</span>
                                </button>
                            </div>
                        @endif
                    </div>
                </header>

                {{-- Payment instructions (pending) --}}
                @if ($isPending && !empty($order->payment_instructions))
                    <section data-payment-instructions class="animate-rise" style="animation-delay: 120ms">
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-[0_20px_40px_-20px_rgba(0,0,0,0.06)] overflow-hidden">
                            <div class="px-6 sm:px-8 py-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div>
                                    <p class="text-[11px] font-mono font-bold uppercase tracking-widest text-slate-400">{{ __('store.pay_instructions_title') }}</p>
                                    <p class="mt-1 text-sm text-slate-600">
                                        {{ __('store.pay_method') }}: <span class="font-semibold text-slate-900">{{ $paymentMethodLabel }}</span>
                                    </p>
                                </div>
                                <div class="text-left sm:text-right">
                                    <p class="text-[11px] font-mono font-bold uppercase tracking-widest text-slate-400">{{ __('store.pay_amount') }}</p>
                                    <p class="font-display text-3xl text-primary">{{ $order->getFormattedTotal() }}</p>
                                </div>
                            </div>

                            <div class="px-6 sm:px-8 py-6">
                                {{-- Virtual Account / E-Channel --}}
                                @if (in_array($methodType, ['bca_va', 'bni_va', 'bri_va', 'permata_va', 'mandiri_va'], true))
                                    <div class="space-y-6">
                                        @if ($vaNumber)
                                            <div>
                                                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2.5">
                                                    {{ $methodType === 'mandiri_va' ? __('store.pay_bill_key') : __('store.pay_va_number') }}
                                                </p>
                                                <div class="flex items-center gap-3 flex-wrap">
                                                    <p id="payment-code" class="font-mono text-2xl sm:text-3xl font-bold text-slate-900 tracking-widest select-all bg-slate-50 border border-slate-200 rounded-xl px-5 py-4">
                                                        {{ $vaNumber }}
                                                    </p>
                                                    <button type="button" data-copy="#payment-code"
                                                        class="px-4 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 active:scale-[0.98] text-slate-700 text-xs font-semibold uppercase tracking-wider transition-colors cursor-pointer shrink-0">
                                                        {{ __('store.pay_copy') }}
                                                    </button>
                                                </div>
                                            </div>
                                        @endif

                                        @if ($methodType === 'mandiri_va' && !empty($order->getInstructions()['biller_code']))
                                            <div>
                                                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">{{ __('store.pay_biller_code') }}</p>
                                                <p class="font-mono text-xl font-bold text-slate-900">{{ $order->getInstructions()['biller_code'] }}</p>
                                            </div>
                                        @endif

                                        <ol class="space-y-2.5 text-sm text-slate-600">
                                            @foreach (__('store.pay_va_steps') as $step)
                                                <li class="flex items-start gap-3">
                                                    <span class="w-6 h-6 rounded-full bg-primary/10 text-primary text-xs font-mono font-bold flex items-center justify-center shrink-0 mt-0.5">{{ $loop->iteration }}</span>
                                                    <span>{{ $step }}</span>
                                                </li>
                                            @endforeach
                                        </ol>
                                    </div>

                                {{-- QRIS / E-Wallet (QR scan) --}}
                                @elseif ($qrUrl || $deepLink)
                                    <div class="flex flex-col sm:flex-row items-center gap-8">
                                        @if ($qrUrl)
                                            <div class="text-center shrink-0">
                                                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">{{ __('store.pay_scan_qr') }}</p>
                                                <img src="{{ $qrUrl }}" alt="{{ __('store.pay_scan_qr') }}"
                                                    class="w-52 h-52 rounded-2xl border-2 border-slate-200 bg-white p-2 object-contain">
                                            </div>
                                        @endif
                                        <div class="flex-1 space-y-4 text-sm text-slate-600">
                                            <ol class="space-y-2.5">
                                                @foreach (__('store.pay_qr_steps') as $step)
                                                    <li class="flex items-start gap-3">
                                                        <span class="w-6 h-6 rounded-full bg-primary/10 text-primary text-xs font-mono font-bold flex items-center justify-center shrink-0 mt-0.5">{{ $loop->iteration }}</span>
                                                        <span>{{ $step }}</span>
                                                    </li>
                                                @endforeach
                                            </ol>
                                            @if ($deepLink)
                                                <a href="{{ $deepLink }}" target="_blank" rel="noopener"
                                                    class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-primary hover:bg-primary-hover active:scale-[0.98] text-white text-xs font-semibold uppercase tracking-wider transition-all duration-200 shadow-sm cursor-pointer">
                                                    {{ __('store.pay_open_app') }}
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>

                            {{-- Expiry + Already Paid --}}
                            <div class="px-6 sm:px-8 py-5 border-t border-slate-100 bg-slate-50/60 flex flex-col sm:flex-row items-center justify-between gap-4">
                                @if ($order->expires_at)
                                    <div class="text-xs text-slate-500">
                                        <span class="font-mono font-bold uppercase tracking-wider text-amber-600">{{ __('store.pay_expires_in') }}</span>
                                        <span id="payment-expiry" data-expires="{{ $order->expires_at->timestamp }}" class="font-mono font-bold text-slate-800 ml-1 tabular-nums">--:--:--</span>
                                    </div>
                                @endif
                                <button type="button" id="btn-paid-check"
                                    class="px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-500 active:scale-[0.98] text-white text-xs font-semibold uppercase tracking-wider transition-all duration-200 shadow-sm cursor-pointer">
                                    {{ __('store.pay_i_have_paid') }}
                                </button>
                            </div>
                        </div>
                    </section>
                @endif

                {{-- Recovery guidance: failed / expired --}}
                @if ($isFailed)
                    <section class="animate-rise" style="animation-delay: 120ms">
                        <div class="rounded-2xl border border-rose-200 bg-rose-50/60 px-6 sm:px-8 py-6">
                            <div class="flex items-start gap-4">
                                <span class="w-10 h-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center shrink-0 border border-rose-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </span>
                                <div class="space-y-2 min-w-0">
                                    <h2 class="font-display text-xl sm:text-2xl text-slate-900 uppercase">{{ __('store.order_status_recovery_title') }}</h2>
                                    <p class="text-sm text-slate-600 leading-relaxed max-w-[60ch]">{{ __('store.order_status_recovery_desc') }}</p>
                                    <div class="pt-2 flex flex-wrap gap-3">
                                        <a href="{{ route('store.index') }}"
                                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary hover:bg-primary-hover active:scale-[0.98] text-white text-xs font-semibold uppercase tracking-wider transition-all duration-200 shadow-sm cursor-pointer">
                                            {{ __('store.order_browse_more') }}
                                        </a>
                                        <a href="{{ url('/contact') }}"
                                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 text-slate-800 text-xs font-semibold uppercase tracking-wider transition-colors">
                                            {{ __('store.order_contact_support') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                @endif

                {{-- Confirmation note: paid --}}
                @if ($isPaid)
                    <section class="animate-rise" style="animation-delay: 120ms">
                        <div class="rounded-2xl border border-emerald-200 bg-emerald-50/60 px-6 sm:px-8 py-6">
                            <div class="flex items-start gap-4">
                                <span class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 border border-emerald-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </span>
                                <div class="min-w-0">
                                    <h2 class="font-display text-xl sm:text-2xl text-slate-900 uppercase">{{ __('store.order_status_paid_note_title') }}</h2>
                                    <p class="text-sm text-slate-600 leading-relaxed max-w-[60ch] mt-1">{{ __('store.order_status_paid_note_desc') }}</p>
                                </div>
                            </div>
                        </div>
                    </section>
                @endif
            </div>

            {{-- RIGHT: sticky order details --}}
            <aside class="lg:col-span-5 lg:sticky lg:top-24 animate-rise" style="animation-delay: 200ms">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-[0_20px_40px_-20px_rgba(0,0,0,0.06)] overflow-hidden">

                    <div class="px-6 sm:px-8 py-5 border-b border-slate-100">
                        <p class="text-[11px] font-mono font-bold uppercase tracking-widest text-slate-400">{{ __('store.checkout_order_summary') }}</p>
                        <div class="mt-3 space-y-1.5 text-xs text-slate-500">
                            <div class="flex justify-between">
                                <span>{{ __('store.cart_subtotal') }}</span>
                                <span class="font-mono font-semibold text-slate-700">{{ $order->getFormattedSubtotal() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>{{ __('store.checkout_shipping_fee') }}</span>
                                <span class="font-mono font-semibold text-slate-700">{{ $order->getFormattedShipping() }}</span>
                            </div>
                            <div class="flex justify-between items-baseline pt-2 mt-1 border-t border-slate-100">
                                <span class="font-semibold text-slate-900">{{ __('store.checkout_total') }}</span>
                                <span class="font-display text-3xl text-primary">{{ $order->getFormattedTotal() }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="px-6 sm:px-8 py-5 border-b border-slate-100">
                        <p class="text-[11px] font-mono font-bold uppercase tracking-widest text-slate-400 mb-3">{{ __('store.order_items') }} <span class="text-slate-300">/ {{ $order->items->count() }}</span></p>
                        <div class="divide-y divide-slate-100 max-h-64 overflow-y-auto pr-1">
                            @foreach ($order->items as $item)
                                <div class="py-3 flex items-start justify-between gap-4">
                                    <div class="min-w-0">
                                        <h4 class="font-display text-sm sm:text-base text-slate-900 uppercase leading-tight truncate">{{ $item->product_name }}</h4>
                                        <p class="text-slate-500 text-[11px] mt-0.5">{{ $item->weight }} <span class="text-slate-300">/</span> {{ ucfirst(str_replace('_', ' ', $item->grind_size)) }}</p>
                                        <p class="text-slate-400 text-[10px] font-mono">{{ __('store.order_qty') }}: {{ $item->quantity }}</p>
                                    </div>
                                    <span class="font-mono font-semibold text-slate-900 shrink-0">{{ $item->getFormattedSubtotal() }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="px-6 sm:px-8 py-5 border-b border-slate-100 grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs sm:text-sm">
                        <div class="min-w-0">
                            <p class="text-[11px] font-mono font-bold uppercase tracking-widest text-slate-400 mb-2">{{ __('store.order_customer') }}</p>
                            <p class="font-semibold text-slate-900">{{ $order->customer_name }}</p>
                            <p class="text-slate-600 mt-0.5 break-all">{{ $order->customer_email }}</p>
                            <p class="text-slate-600">{{ $order->customer_phone }}</p>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[11px] font-mono font-bold uppercase tracking-widest text-slate-400 mb-2">{{ __('store.order_shipping_to') }}</p>
                            <p class="text-slate-600 leading-relaxed">{{ $order->shipping_address }}</p>
                            <p class="text-slate-900 font-medium mt-1">{{ $order->city }}{{ $order->province ? ', ' . $order->province : '' }}{{ $order->postal_code ? " ({$order->postal_code})" : '' }}</p>
                            <p class="text-slate-400 text-[11px] mt-1 font-mono">{{ __('store.order_courier') }}: {{ $order->courier }}</p>
                        </div>
                    </div>

                    <div class="px-6 sm:px-8 py-5 space-y-3">
                        <a href="{{ route('store.index') }}"
                            class="w-full flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-primary hover:bg-primary-hover active:scale-[0.98] text-white text-xs font-semibold uppercase tracking-wider transition-all duration-200 shadow-sm cursor-pointer">
                            {{ __('store.order_browse_more') }}
                        </a>
                        <a href="{{ url('/') }}"
                            class="w-full flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 text-slate-800 text-xs font-semibold uppercase tracking-wider transition-colors">
                            {{ __('store.order_back_home') }}
                        </a>
                    </div>
                </div>
            </aside>

        </div>
    </div>
</div>
@endsection
