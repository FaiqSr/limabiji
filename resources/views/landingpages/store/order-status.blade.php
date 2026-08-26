{{-- resources/views/landingpages/store/order-status.blade.php --}}
@extends('layouts.store')

@php
    $locale = app()->getLocale();
@endphp

@push('title', __('store.order_status_title') . ' #' . $order->order_number . ': Lima Biji Agritech')

@push('meta')
    <meta name="robots" content="noindex, nofollow">
@endpush

@push('scripts')
    @if ($order->payment_status === 'pending' && !empty($snapJsUrl) && !empty($clientKey))
        <script src="{{ $snapJsUrl }}" data-client-key="{{ $clientKey }}"></script>
    @endif
@endpush

@section('content')
<div class="bg-[#fafaf9] text-slate-800 min-h-screen">
    <div class="container mx-auto px-5 lg:px-8 py-10 lg:py-16 max-w-4xl">

        {{-- Flash Alerts --}}
        @if (session('success'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center gap-3 shadow-xs">
                <div class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                </div>
                <span class="text-xs sm:text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 flex items-center gap-3 shadow-xs">
                <div class="w-5 h-5 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </div>
                <span class="text-xs sm:text-sm font-medium">{{ session('error') }}</span>
            </div>
        @endif

        {{-- Status Header Card --}}
        <div class="bg-white p-8 sm:p-10 rounded-2xl border mb-8 text-center relative overflow-hidden shadow-sm
            {{ $order->payment_status === 'paid'
                ? 'border-emerald-200'
                : ($order->payment_status === 'pending'
                    ? 'border-amber-200'
                    : 'border-rose-200') }}">

            {{-- Icon --}}
            <div class="w-16 h-16 rounded-2xl mx-auto flex items-center justify-center mb-4 shadow-sm
                {{ $order->payment_status === 'paid'
                    ? 'bg-emerald-50 text-emerald-600 border border-emerald-200'
                    : ($order->payment_status === 'pending'
                        ? 'bg-amber-50 text-amber-600 border border-amber-200'
                        : 'bg-rose-50 text-rose-600 border border-rose-200') }}">
                @if ($order->payment_status === 'paid')
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                @elseif ($order->payment_status === 'pending')
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                @else
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                @endif
            </div>

            <span class="inline-block px-3 py-1 rounded-full text-xs font-mono font-bold uppercase tracking-wider mb-2
                {{ $order->payment_status === 'paid'
                    ? 'bg-emerald-100 text-emerald-800'
                    : ($order->payment_status === 'pending'
                        ? 'bg-amber-100 text-amber-800'
                        : 'bg-rose-100 text-rose-800') }}">
                {{ $order->payment_status === 'paid' ? __('store.order_status_paid') : ($order->payment_status === 'pending' ? __('store.order_status_pending') : __('store.order_status_failed')) }}
            </span>

            <h1 class="font-display text-3xl sm:text-4xl lg:text-5xl text-slate-900 uppercase mt-2">
                {{ $order->order_number }}
            </h1>

            <p class="text-slate-500 text-xs sm:text-sm max-w-md mx-auto mt-2">
                {{ __('store.order_date') }}: {{ $order->created_at->format('d F Y, H:i') }} WIB
            </p>

            {{-- Pay Now Button for Pending Status --}}
            @if ($order->payment_status === 'pending')
                <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
                    <button type="button" id="btn-pay-snap"
                        class="w-full sm:w-auto px-8 py-3.5 rounded-xl bg-primary hover:bg-primary-hover active:scale-[0.98] text-white font-semibold text-xs sm:text-sm uppercase tracking-wider transition-all duration-200 shadow-md cursor-pointer flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>{{ __('store.order_pay_now_btn') }}</span>
                    </button>
                </div>
            @endif
        </div>

        {{-- Order Details Breakdown --}}
        <div class="bg-white p-6 sm:p-10 rounded-2xl space-y-8 border border-slate-200 shadow-sm">

            {{-- Customer & Shipping Info --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-6 border-b border-slate-100 text-xs sm:text-sm">
                <div>
                    <h3 class="text-xs font-mono font-bold uppercase text-primary tracking-wider mb-2">Customer</h3>
                    <p class="font-bold text-slate-900 text-base">{{ $order->customer_name }}</p>
                    <p class="text-slate-600 mt-0.5">{{ $order->customer_email }}</p>
                    <p class="text-slate-600">{{ $order->customer_phone }}</p>
                </div>
                <div>
                    <h3 class="text-xs font-mono font-bold uppercase text-primary tracking-wider mb-2">{{ __('store.order_shipping_to') }}</h3>
                    <p class="text-slate-600 leading-relaxed">{{ $order->shipping_address }}</p>
                    <p class="text-slate-900 font-medium mt-1">{{ $order->city }} {{ $order->postal_code ? "({$order->postal_code})" : '' }}</p>
                    <p class="text-xs text-slate-400 mt-0.5">Courier: {{ $order->courier }}</p>
                </div>
            </div>

            {{-- Ordered Items Table --}}
            <div>
                <h3 class="text-xs font-mono font-bold uppercase text-primary tracking-wider mb-4">{{ __('store.order_items') }}</h3>
                <div class="divide-y divide-slate-100">
                    @foreach ($order->items as $item)
                        <div class="py-3 flex items-center justify-between gap-4 text-xs sm:text-sm">
                            <div>
                                <h4 class="font-display text-base sm:text-lg text-slate-900 uppercase leading-tight">{{ $item->product_name }}</h4>
                                <p class="text-slate-500 text-xs mt-0.5">
                                    {{ $item->weight }} • {{ ucfirst(str_replace('_', ' ', $item->grind_size)) }} • Qty: {{ $item->quantity }}
                                </p>
                            </div>
                            <span class="font-semibold text-slate-900 font-mono">
                                {{ $item->getFormattedSubtotal() }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Pricing Totals --}}
            <div class="pt-6 border-t border-slate-100 space-y-2 text-xs sm:text-sm">
                <div class="flex justify-between text-slate-600">
                    <span>{{ __('store.cart_subtotal') }}</span>
                    <span class="font-semibold text-slate-900 font-mono">{{ $order->getFormattedSubtotal() }}</span>
                </div>
                <div class="flex justify-between text-slate-600">
                    <span>{{ __('store.checkout_shipping_fee') }} ({{ $order->courier }})</span>
                    <span class="font-semibold text-slate-900 font-mono">{{ $order->getFormattedShipping() }}</span>
                </div>
                <div class="flex justify-between text-base sm:text-lg font-bold text-slate-900 pt-3 border-t border-slate-100">
                    <span>{{ __('store.checkout_total') }}</span>
                    <span class="font-display text-3xl text-primary">{{ $order->getFormattedTotal() }}</span>
                </div>
            </div>

            {{-- Dev Simulator Box --}}
            @if ($order->payment_status === 'pending')
                <div class="p-4 sm:p-5 rounded-xl bg-amber-50/70 border border-amber-200 space-y-3">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                        <h4 class="text-xs font-bold font-mono text-amber-800 uppercase">{{ __('store.dev_simulate_title') }}</h4>
                    </div>
                    <p class="text-[11px] text-slate-600 leading-relaxed">
                        {{ __('store.dev_simulate_desc') }}
                    </p>
                    <form method="POST" action="{{ route('store.order.simulate', $order->order_number) }}">
                        @csrf
                        <input type="hidden" name="action" value="pay">
                        <button type="submit"
                            class="px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-500 active:scale-95 text-white text-xs font-semibold uppercase tracking-wider transition-all cursor-pointer shadow-xs flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>{{ __('store.dev_simulate_btn') }}</span>
                        </button>
                    </form>
                </div>
            @endif

            {{-- Footer Back Buttons --}}
            <div class="pt-6 flex flex-wrap items-center justify-between gap-4 border-t border-slate-100">
                <a href="{{ route('store.index') }}"
                    class="text-xs font-semibold text-primary hover:underline uppercase tracking-wider flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span>{{ __('store.order_browse_more') }}</span>
                </a>

                <a href="{{ url('/') }}"
                    class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 border border-slate-200 text-slate-800 text-xs font-semibold uppercase tracking-wider transition-colors">
                    {{ __('store.order_back_home') }}
                </a>
            </div>
        </div>
    </div>
</div>

    @if ($order->payment_status === 'pending' && !empty($order->snap_token))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const btnPay = document.getElementById('btn-pay-snap');
                const snapToken = '{{ $order->snap_token }}';

                if (btnPay) {
                    btnPay.addEventListener('click', () => {
                        if (typeof window.snap !== 'undefined' && typeof window.snap.pay === 'function' && !snapToken.startsWith('MOCK_') && !snapToken.startsWith('FALLBACK_')) {
                            window.snap.pay(snapToken, {
                                onSuccess: function(result) {
                                    window.location.reload();
                                },
                                onPending: function(result) {
                                    window.location.reload();
                                },
                                onError: function(result) {
                                    alert('Payment failed or cancelled.');
                                    window.location.reload();
                                },
                                onClose: function() {
                                    console.log('Payment modal closed.');
                                }
                            });
                        } else {
                            // In mock or fallback dev mode, automatically trigger simulation
                            if (confirm('Midtrans Snap SDK in local simulation mode. Would you like to simulate an instant successful payment?')) {
                                const simForm = document.querySelector('form[action*="simulate"]');
                                if (simForm) {
                                    simForm.submit();
                                } else {
                                    window.location.reload();
                                }
                            }
                        }
                    });
                }
            });
        </script>
    @endif
@endsection
