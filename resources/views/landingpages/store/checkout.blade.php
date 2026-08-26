{{-- resources/views/landingpages/store/checkout.blade.php --}}
@extends('layouts.store')

@php
    $locale = app()->getLocale();
@endphp

@push('title', __('store.checkout_title') . ': Lima Biji Agritech')

@push('meta')
    <meta name="description" content="{{ __('store.checkout_title') }}">
    <meta property="og:title" content="{{ __('store.checkout_title') }}: Lima Biji Agritech">
    <meta property="og:description" content="{{ __('store.checkout_title') }}">
    <meta property="og:type" content="website">
    <meta name="robots" content="noindex, nofollow">
@endpush

@section('content')
<div class="bg-[#fafaf9] text-slate-800 min-h-screen">
    <div class="container mx-auto px-5 lg:px-8 py-8 lg:py-12 max-w-7xl">

        {{-- Breadcrumbs --}}
        <div class="flex items-center gap-2 text-xs sm:text-sm text-slate-500 mb-8">
            <a href="{{ route('store.index') }}" class="hover:text-primary transition-colors flex items-center gap-1.5 font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                {{ __('store.detail_back') }}
            </a>
            <span class="text-slate-300">/</span>
            <span class="text-slate-700 font-semibold">{{ __('store.checkout_title') }}</span>
        </div>

        <h1 class="font-display text-4xl sm:text-5xl text-slate-900 uppercase mb-8">
            {{ __('store.checkout_title') }}
        </h1>

        <form action="{{ route('store.checkout.process') }}" method="POST" id="checkout-form">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">

                {{-- Left: Customer Shipping Info --}}
                <div class="lg:col-span-7 space-y-6">
                    <div class="bg-white p-6 sm:p-8 rounded-2xl space-y-5 border border-slate-200 shadow-sm">
                        <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                            <span class="w-7 h-7 rounded-lg bg-primary/10 border border-primary/20 text-primary flex items-center justify-center text-xs font-mono font-bold">1</span>
                            <h2 class="font-display text-2xl text-slate-900 uppercase">{{ __('store.checkout_customer_info') }}</h2>
                        </div>

                        {{-- Name & Email --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-slate-800 font-semibold text-xs uppercase tracking-wider mb-2">
                                    {{ __('store.checkout_name') }} *
                                </label>
                                <input type="text" name="customer_name" value="{{ old('customer_name') }}" required
                                    class="w-full bg-white border @error('customer_name') border-rose-500 @else border-slate-200 @enderror rounded-xl p-3 text-slate-900 placeholder:text-slate-400 text-sm focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors shadow-xs"
                                    placeholder="Rian Kurniawan">
                                @error('customer_name')
                                    <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-slate-800 font-semibold text-xs uppercase tracking-wider mb-2">
                                    {{ __('store.checkout_email') }} *
                                </label>
                                <input type="email" name="customer_email" value="{{ old('customer_email') }}" required
                                    class="w-full bg-white border @error('customer_email') border-rose-500 @else border-slate-200 @enderror rounded-xl p-3 text-slate-900 placeholder:text-slate-400 text-sm focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors shadow-xs"
                                    placeholder="rian@example.com">
                                @error('customer_email')
                                    <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Phone & City --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-slate-800 font-semibold text-xs uppercase tracking-wider mb-2">
                                    {{ __('store.checkout_phone') }} *
                                </label>
                                <input type="tel" name="customer_phone" value="{{ old('customer_phone') }}" required
                                    class="w-full bg-white border @error('customer_phone') border-rose-500 @else border-slate-200 @enderror rounded-xl p-3 text-slate-900 placeholder:text-slate-400 text-sm focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors shadow-xs"
                                    placeholder="081234567890">
                                @error('customer_phone')
                                    <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-slate-800 font-semibold text-xs uppercase tracking-wider mb-2">
                                    {{ __('store.checkout_city') }} *
                                </label>
                                <input type="text" name="city" value="{{ old('city') }}" required
                                    class="w-full bg-white border @error('city') border-rose-500 @else border-slate-200 @enderror rounded-xl p-3 text-slate-900 placeholder:text-slate-400 text-sm focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors shadow-xs"
                                    placeholder="Bogor / Jakarta Selatan">
                                @error('city')
                                    <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Complete Address --}}
                        <div>
                            <label class="block text-slate-800 font-semibold text-xs uppercase tracking-wider mb-2">
                                {{ __('store.checkout_address') }} *
                            </label>
                            <textarea name="shipping_address" required rows="3"
                                class="w-full bg-white border @error('shipping_address') border-rose-500 @else border-slate-200 @enderror rounded-xl p-3 text-slate-900 placeholder:text-slate-400 text-sm focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors shadow-xs"
                                placeholder="Jl. Pajajaran No. 88, RT 02/RW 05, Kel. Babakan">{{ old('shipping_address') }}</textarea>
                            @error('shipping_address')
                                <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Postal Code & Courier --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-slate-800 font-semibold text-xs uppercase tracking-wider mb-2">
                                    {{ __('store.checkout_postal') }}
                                </label>
                                <input type="text" name="postal_code" value="{{ old('postal_code') }}"
                                    class="w-full bg-white border border-slate-200 rounded-xl p-3 text-slate-900 placeholder:text-slate-400 text-sm focus:border-primary focus:outline-none transition-colors shadow-xs"
                                    placeholder="16128">
                            </div>

                            <div>
                                <label class="block text-slate-800 font-semibold text-xs uppercase tracking-wider mb-2">
                                    {{ __('store.checkout_courier') }} *
                                </label>
                                <select name="courier" id="courier-select"
                                    class="w-full bg-white border border-slate-200 rounded-xl p-3 text-slate-900 text-sm focus:border-primary focus:outline-none transition-colors shadow-xs">
                                    <option value="JNE REG" data-cost="25000" {{ old('courier') === 'JNE REG' ? 'selected' : '' }}>
                                        JNE REG (Rp 25.000)
                                    </option>
                                    <option value="J&T Express" data-cost="28000" {{ old('courier') === 'J&T Express' ? 'selected' : '' }}>
                                        J&T Express (Rp 28.000)
                                    </option>
                                    <option value="SiCepat BEST" data-cost="35000" {{ old('courier') === 'SiCepat BEST' ? 'selected' : '' }}>
                                        SiCepat BEST 1-Day (Rp 35.000)
                                    </option>
                                    <option value="GoSend / Grab Instant" data-cost="45000" {{ old('courier') === 'GoSend / Grab Instant' ? 'selected' : '' }}>
                                        GoSend / Grab Instant (Rp 45.000)
                                    </option>
                                </select>
                            </div>
                        </div>

                        {{-- Notes --}}
                        <div>
                            <label class="block text-slate-800 font-semibold text-xs uppercase tracking-wider mb-2">
                                {{ __('store.checkout_notes') }}
                            </label>
                            <input type="text" name="notes" value="{{ old('notes') }}"
                                class="w-full bg-white border border-slate-200 rounded-xl p-3 text-slate-900 placeholder:text-slate-400 text-sm focus:border-primary focus:outline-none transition-colors shadow-xs"
                                placeholder="Gilingan untuk V60 rasio 1:15 atau titip di pos sekuriti">
                        </div>
                    </div>
                </div>

                {{-- Right: Order Summary Card --}}
                <div class="lg:col-span-5 space-y-6">
                    <div class="bg-white p-6 sm:p-8 rounded-2xl space-y-5 border border-slate-200 shadow-sm sticky top-24">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <h2 class="font-display text-2xl text-slate-900 uppercase">{{ __('store.checkout_order_summary') }}</h2>
                            <span class="text-xs font-mono font-bold text-primary bg-primary/10 border border-primary/20 px-2.5 py-1 rounded-md">
                                {{ $itemCount }} {{ $itemCount > 1 ? 'items' : 'item' }}
                            </span>
                        </div>

                        {{-- Item list --}}
                        <div class="divide-y divide-slate-100 max-h-72 overflow-y-auto pr-1">
                            @foreach ($cart as $item)
                                @php
                                    $itemDisplayName = ($locale === 'id' && !empty($item['name_id'])) ? $item['name_id'] : $item['name'];
                                @endphp
                                <div class="py-3 flex items-start justify-between gap-3 text-xs">
                                    <div class="flex items-start gap-3 min-w-0">
                                        <img src="{{ $item['image'] ?: asset('assets/images/limabiji/toko.webp') }}"
                                            alt="{{ $itemDisplayName }}" class="w-12 h-12 rounded-lg object-cover border border-slate-200 shrink-0">
                                        <div class="min-w-0">
                                            <p class="font-display text-sm text-slate-900 uppercase truncate">{{ $itemDisplayName }}</p>
                                            <p class="text-slate-500 text-[11px] mt-0.5">{{ $item['weight'] }} • {{ ucfirst(str_replace('_', ' ', $item['grind_size'])) }}</p>
                                            <p class="text-slate-400 text-[10px]">Qty: {{ $item['quantity'] }} × Rp {{ number_format($item['unit_price'], 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                    <span class="font-semibold text-slate-900 shrink-0">
                                        Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                    </span>
                                </div>
                            @endforeach
                        </div>

                        {{-- Calculation Breakdown --}}
                        <div class="pt-4 border-t border-slate-100 space-y-2.5 text-xs">
                            <div class="flex justify-between text-slate-600">
                                <span>{{ __('store.cart_subtotal') }}</span>
                                <span class="font-semibold text-slate-900" id="summary-subtotal" data-subtotal="{{ $subtotal }}">
                                    Rp {{ number_format($subtotal, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex justify-between text-slate-600">
                                <span>{{ __('store.checkout_shipping_fee') }}</span>
                                <span class="font-semibold text-slate-900" id="summary-shipping">
                                    Rp {{ number_format($defaultShippingCost, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex justify-between text-sm sm:text-base font-bold text-slate-900 pt-2 border-t border-slate-100">
                                <span>{{ __('store.checkout_total') }}</span>
                                <span class="font-display text-2xl text-primary" id="summary-total">
                                    Rp {{ number_format($total, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        {{-- Payment Gateway Trigger Button --}}
                        <div class="pt-2">
                            <button type="submit" id="btn-submit-order"
                                class="w-full flex items-center justify-center gap-2.5 px-6 py-4 rounded-xl bg-primary hover:bg-primary-hover active:scale-[0.98] text-white font-semibold text-sm uppercase tracking-wider transition-all duration-200 shadow-md cursor-pointer">
                                <span>{{ __('store.checkout_pay_midtrans') }}</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const courierSelect = document.getElementById('courier-select');
            const summarySubtotal = document.getElementById('summary-subtotal');
            const summaryShipping = document.getElementById('summary-shipping');
            const summaryTotal = document.getElementById('summary-total');

            function recalculateTotals() {
                const selectedOption = courierSelect.options[courierSelect.selectedIndex];
                const shippingCost = parseInt(selectedOption.getAttribute('data-cost') || '25000');
                const subtotal = parseInt(summarySubtotal.getAttribute('data-subtotal') || '0');
                const total = subtotal + shippingCost;

                summaryShipping.textContent = 'Rp ' + shippingCost.toLocaleString('id-ID');
                summaryTotal.textContent = 'Rp ' + total.toLocaleString('id-ID');
            }

            if (courierSelect) {
                courierSelect.addEventListener('change', recalculateTotals);
            }
        });
    </script>
    @endpush
@endsection
