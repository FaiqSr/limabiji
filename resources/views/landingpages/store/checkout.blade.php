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

                {{-- LEFT: blocks 1-3 --}}
                <div class="lg:col-span-7 space-y-6">

                    {{-- Block 1: Customer Information --}}
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

                        {{-- Phone --}}
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

                        {{-- Province / City / District (searchable combo boxes) --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-slate-800 font-semibold text-xs uppercase tracking-wider mb-2">
                                    {{ __('store.checkout_province') }} *
                                </label>
                                <div class="combo relative" data-url="{{ route('shipping.provinces') }}"
                                    data-value-name="province" data-placeholder="{{ __('store.checkout_select') }}">
                                    <input type="hidden" name="province" value="{{ old('province') }}">
                                    <div class="combo-field relative">
                                        <input type="text" class="combo-input w-full bg-white border @error('province') border-rose-500 @else border-slate-200 @enderror rounded-xl p-3 pr-10 text-slate-900 placeholder:text-slate-400 text-sm focus:border-primary focus:outline-none transition-colors shadow-xs"
                                            placeholder="{{ __('store.checkout_select') }}" readonly autocomplete="off">
                                        <span class="combo-arrow absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                        </span>
                                    </div>
                                    <div class="combo-list hidden mt-1 absolute z-20 w-full max-h-56 overflow-y-auto bg-white border border-slate-200 rounded-xl shadow-lg divide-y divide-slate-100"></div>
                                </div>
                                @error('province')
                                    <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-slate-800 font-semibold text-xs uppercase tracking-wider mb-2">
                                    {{ __('store.checkout_city') }} *
                                </label>
                                <div class="combo relative" data-url="{{ route('shipping.cities', ['provinceId' => 'PID']) }}"
                                    data-value-name="city" data-placeholder="{{ __('store.checkout_select_first') }}"
                                    data-disabled-placeholder="{{ __('store.checkout_select_first') }}">
                                    <input type="hidden" name="city" value="{{ old('city') }}">
                                    <div class="combo-field relative">
                                        <input type="text" class="combo-input w-full bg-slate-50 cursor-not-allowed border border-slate-200 rounded-xl p-3 pr-10 text-slate-900 placeholder:text-slate-400 text-sm focus:border-primary focus:outline-none transition-colors shadow-xs"
                                            placeholder="{{ __('store.checkout_select_first') }}" readonly disabled autocomplete="off">
                                        <span class="combo-arrow absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                        </span>
                                    </div>
                                    <div class="combo-list hidden mt-1 absolute z-20 w-full max-h-56 overflow-y-auto bg-white border border-slate-200 rounded-xl shadow-lg divide-y divide-slate-100"></div>
                                </div>
                                @error('city')
                                    <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-slate-800 font-semibold text-xs uppercase tracking-wider mb-2">
                                {{ __('store.checkout_district') }} *
                            </label>
                            <div class="combo relative" data-url="{{ route('shipping.districts', ['cityId' => 'CID']) }}"
                                data-value-name="shipping_district_id" data-placeholder="{{ __('store.checkout_select_first') }}"
                                data-disabled-placeholder="{{ __('store.checkout_select_first') }}">
                                <input type="hidden" name="shipping_district_id" value="{{ old('shipping_district_id') }}">
                                <div class="combo-field relative">
                                    <input type="text" class="combo-input w-full bg-slate-50 cursor-not-allowed border border-slate-200 rounded-xl p-3 pr-10 text-slate-900 placeholder:text-slate-400 text-sm focus:border-primary focus:outline-none transition-colors shadow-xs"
                                        placeholder="{{ __('store.checkout_select_first') }}" readonly disabled autocomplete="off">
                                    <span class="combo-arrow absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </span>
                                </div>
                                <div class="combo-list hidden mt-1 absolute z-20 w-full max-h-56 overflow-y-auto bg-white border border-slate-200 rounded-xl shadow-lg divide-y divide-slate-100"></div>
                            </div>
                            @error('shipping_district_id')
                                <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
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

                        {{-- Postal Code & Notes --}}
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
                                    {{ __('store.checkout_notes') }}
                                </label>
                                <input type="text" name="notes" value="{{ old('notes') }}"
                                    class="w-full bg-white border border-slate-200 rounded-xl p-3 text-slate-900 placeholder:text-slate-400 text-sm focus:border-primary focus:outline-none transition-colors shadow-xs"
                                    placeholder="Gilingan untuk V60 rasio 1:15 atau titip di pos sekuriti">
                            </div>
                        </div>
                    </div>

                    {{-- Block 2: Courier --}}
                    <div class="bg-white p-6 sm:p-8 rounded-2xl space-y-5 border border-slate-200 shadow-sm">
                        <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                            <span class="w-7 h-7 rounded-lg bg-primary/10 border border-primary/20 text-primary flex items-center justify-center text-xs font-mono font-bold">2</span>
                            <h2 class="font-display text-2xl text-slate-900 uppercase">{{ __('store.checkout_courier') }}</h2>
                        </div>

                        <div id="courier-empty" class="text-sm text-slate-500">
                            {{ __('store.checkout_courier_hint') }}
                        </div>
                        <div id="courier-loading" class="hidden text-sm text-slate-500">
                            {{ __('store.shipping_loading') }}
                        </div>

                        <div id="courier-list" class="grid grid-cols-1 gap-3"></div>

                        <p id="courier-error" class="hidden text-rose-600 text-xs mt-1"></p>
                        @error('courier')
                            <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Block 3: Payment Method --}}
                    <div class="bg-white p-6 sm:p-8 rounded-2xl space-y-5 border border-slate-200 shadow-sm">
                        <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                            <span class="w-7 h-7 rounded-lg bg-primary/10 border border-primary/20 text-primary flex items-center justify-center text-xs font-mono font-bold">3</span>
                            <h2 class="font-display text-2xl text-slate-900 uppercase">{{ __('store.checkout_payment_method') }}</h2>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3" id="payment-methods">
                            @foreach ($paymentMethods as $key => $method)
                                <label
                                    class="payment-method-card relative flex items-start gap-3 p-4 rounded-xl border cursor-pointer transition-all duration-200 select-none
                                        border-slate-200 hover:border-primary/50 bg-white
                                        has-[:checked]:border-primary has-[:checked]:ring-2 has-[:checked]:ring-primary/20 has-[:checked]:bg-primary/5">
                                    <span class="w-5 h-5 rounded-full border-2 shrink-0 mt-0.5 flex items-center justify-center
                                        border-slate-300 has-[:checked]:border-primary has-[:checked]:[&>span]:bg-primary">
                                        <input type="radio" name="payment_method" value="{{ $key }}"
                                            class="sr-only"
                                            {{ old('payment_method') === $key || $loop->first ? 'checked' : '' }}>
                                        <span class="w-2.5 h-2.5 rounded-full bg-transparent"></span>
                                    </span>
                                    <span class="min-w-0">
                                        <span class="block font-semibold text-sm text-slate-900">{{ __('store.pay_method_'.$key) }}</span>
                                        <span class="block text-[11px] text-slate-500 mt-0.5">{{ __('store.pay_method_'.$key.'_desc') }}</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        @error('payment_method')
                            <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- RIGHT: Block 4 Order Summary --}}
                <div class="lg:col-span-5 space-y-6">
                    <div class="bg-white p-6 sm:p-8 rounded-2xl space-y-5 border border-slate-200 shadow-sm sticky top-24">
                        <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                            <span class="w-7 h-7 rounded-lg bg-primary/10 border border-primary/20 text-primary flex items-center justify-center text-xs font-mono font-bold">4</span>
                            <h2 class="font-display text-2xl text-slate-900 uppercase">{{ __('store.checkout_order_summary') }}</h2>
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
                                    {{ __('store.shipping_pending') }}
                                </span>
                            </div>
                            <div class="flex justify-between text-sm sm:text-base font-bold text-slate-900 pt-2 border-t border-slate-100">
                                <span>{{ __('store.checkout_total') }}</span>
                                <span class="font-display text-2xl text-primary" id="summary-total">
                                    Rp {{ number_format($total, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        {{-- Submit --}}
                        <div class="pt-2">
                            <button type="submit" id="btn-submit-order"
                                class="w-full flex items-center justify-center gap-2.5 px-6 py-4 rounded-xl bg-primary hover:bg-primary-hover active:scale-[0.98] text-white font-semibold text-sm uppercase tracking-wider transition-all duration-200 shadow-md cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"
                                disabled>
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
            const courierList = document.getElementById('courier-list');
            const courierEmpty = document.getElementById('courier-empty');
            const courierLoading = document.getElementById('courier-loading');
            const courierError = document.getElementById('courier-error');
            const summaryShipping = document.getElementById('summary-shipping');
            const summaryTotal = document.getElementById('summary-total');
            const submitBtn = document.getElementById('btn-submit-order');

            function fmt(n) {
                return 'Rp ' + Number(n).toLocaleString('id-ID');
            }

            let selectedShippingCost = null; // numeric cost when a courier is picked
            let visibleCosts = [];            // current cost payload for district

            function refreshTotals() {
                summaryShipping.textContent = selectedShippingCost === null
                    ? '{{ __('store.shipping_pending') }}'
                    : fmt(selectedShippingCost);
                summaryTotal.textContent = fmt((parseInt(document.getElementById('summary-subtotal').getAttribute('data-subtotal')) || 0) + (selectedShippingCost ?? 0));
                submitBtn.disabled = selectedShippingCost === null;
            }

            async function fetchJson(url, options) {
                const res = await fetch(url, options);
                const json = await res.json().catch(() => null);
                if (!res.ok || !json || json.status !== 'success') {
                    throw new Error((json && json.message) || '{{ __('store.shipping_cost_not_available') }}');
                }
                return json;
            }

            function showCourierError(msg) {
                courierEmpty.classList.remove('hidden');
                courierError.textContent = msg;
                courierError.classList.remove('hidden');
            }

            function hideCouriers() {
                visibleCosts = [];
                selectedShippingCost = null;
                courierList.innerHTML = '';
                courierEmpty.classList.remove('hidden');
                courierLoading.classList.add('hidden');
                courierError.classList.add('hidden');
                refreshTotals();
            }

            // ---- ComboBox ----
            function createCombo(el, onSelect) {
                const hidden = el.querySelector('input[type="hidden"]');
                const input = el.querySelector('.combo-input');
                const list = el.querySelector('.combo-list');
                const placeholder = el.dataset.placeholder || '';
                const disabledPlaceholder = el.dataset.disabledPlaceholder || placeholder;
                let items = [];      // [{id, name, ...}]
                let selected = null; // selected item
                let loaded = false;

                function close() {
                    list.classList.add('hidden');
                }

                function setDisabled(disabled) {
                    input.disabled = disabled;
                    input.classList.toggle('cursor-not-allowed', disabled);
                    if (disabled) {
                        close();
                        selected = null;
                        hidden.value = '';
                        input.value = '';
                        input.placeholder = disabledPlaceholder;
                    } else {
                        input.placeholder = placeholder;
                    }
                }

                function setItems(data, keepValue) {
                    items = data || [];
                    loaded = true;
                    render(keepValue);
                }

                function render(filterText) {
                    list.innerHTML = '';
                    const q = (filterText || '').toLowerCase().trim();
                    const filtered = q ? items.filter(it => (it.name || '').toLowerCase().includes(q)) : items;

                    if (!filtered.length) {
                        const empty = document.createElement('div');
                        empty.className = 'px-4 py-3 text-sm text-slate-400 text-center';
                        empty.textContent = '{{ __('store.checkout_no_result') }}';
                        list.appendChild(empty);
                        return;
                    }

                    filtered.forEach(it => {
                        const row = document.createElement('button');
                        row.type = 'button';
                        row.className = 'w-full text-left px-4 py-2.5 text-sm text-slate-700 hover:bg-primary/5 hover:text-primary transition-colors ' + (selected && selected.id === it.id ? 'bg-primary/5 text-primary' : '');
                        row.textContent = it.name;
                        row.addEventListener('click', () => {
                            selected = it;
                            hidden.value = (el.dataset.valueName === 'shipping_district_id') ? it.id : it.name;
                            input.value = it.name;
                            input.placeholder = '';
                            close();
                            row.classList.add('bg-primary/5');
                            onSelect(it);
                        });
                        list.appendChild(row);
                    });
                }

                // fetch + populate options from server (once)
                async function open() {
                    if (input.disabled) return;
                    const wasLoaded = loaded;
                    if (!wasLoaded) {
                        list.innerHTML = '<div class="px-4 py-3 text-sm text-slate-400 text-center">{{ __('store.shipping_loading') }}</div>';
                        list.classList.remove('hidden');
                        try {
                            const url = el.dataset.url.replace('PID', encodeURIComponent(el._parentId ?? '')).replace('CID', encodeURIComponent(el._parentId ?? ''));
                            const json = await fetchJson(url);
                            setItems(json.data || []);
                        } catch (e) {
                            list.innerHTML = '';
                            list.classList.add('hidden');
                            showCourierError(e.message);
                            return;
                        }
                    }
                    list.classList.remove('hidden');
                    render(input.value);
                }

                // live keyboard search filter
                input.addEventListener('input', () => {
                    if (!loaded) return;
                    list.classList.remove('hidden');
                    render(input.value);
                });

                input.addEventListener('click', (e) => {
                    e.stopPropagation();
                    open();
                });

                document.addEventListener('click', (e) => {
                    if (!el.contains(e.target)) close();
                });

                return {
                    el,
                    setDisabled,
                    setItems,
                    get selected() { return selected; },
                    get hiddenInput() { return hidden; },
                    reset() {
                        loaded = false;
                        selected = null;
                        hidden.value = '';
                        input.value = '';
                        render('');
                        setDisabled(true);
                    },
                };
            }

            const provinceCombo = createCombo(document.querySelector('.combo[data-value-name="province"]'), (it) => {
                provinceCombo.el._parentId = it.id;
                resetCityCombo();
                loadCities(it.id);
            });
            const cityCombo = createCombo(document.querySelector('.combo[data-value-name="city"]'), (it) => {
                cityCombo.el._parentId = it.id;
                resetDistrictCombo();
                loadDistricts(it.id);
            });
            const districtCombo = createCombo(document.querySelector('.combo[data-value-name="shipping_district_id"]'), (it) => {
                loadCouriers(it.id);
            });

            function resetCityCombo() {
                cityCombo.reset();
                resetDistrictCombo();
            }

            function resetDistrictCombo() {
                districtCombo.reset();
                hideCouriers();
            }

            async function loadCities(provinceId) {
                if (!provinceId) return;
                cityCombo.setDisabled(false);
                try {
                    const json = await fetchJson(`{{ route('shipping.cities', ['provinceId' => 'PID']) }}`.replace('PID', provinceId));
                    cityCombo.setItems(json.data || []);
                } catch (e) {
                    showCourierError(e.message);
                }
            }

            async function loadDistricts(cityId) {
                if (!cityId) return;
                districtCombo.setDisabled(false);
                try {
                    const json = await fetchJson(`{{ route('shipping.districts', ['cityId' => 'CID']) }}`.replace('CID', cityId));
                    districtCombo.setItems(json.data || []);
                } catch (e) {
                    showCourierError(e.message);
                }
            }

            async function loadCouriers(districtId) {
                hideCouriers();
                if (!districtId) return;
                courierEmpty.classList.add('hidden');
                courierLoading.classList.remove('hidden');
                courierError.classList.add('hidden');
                try {
                    const json = await fetchJson(`{{ route('shipping.cost') }}`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: new URLSearchParams({ district_id: districtId }),
                    });
                    visibleCosts = json.data || [];
                    courierLoading.classList.add('hidden');
                    renderCouriers();
                } catch (e) {
                    courierLoading.classList.add('hidden');
                    courierEmpty.classList.remove('hidden');
                    courierError.textContent = e.message;
                    courierError.classList.remove('hidden');
                }
            }

            function renderCouriers() {
                courierList.innerHTML = '';
                courierEmpty.classList.toggle('hidden', visibleCosts.length > 0);

                if (!visibleCosts.length) {
                    refreshTotals();
                    return;
                }

                visibleCosts.forEach((c, i) => {
                    const label = document.createElement('label');
                    label.className = 'courier-option relative flex items-start gap-3 p-4 rounded-xl border cursor-pointer transition-all duration-200 select-none border-slate-200 hover:border-primary/50 bg-white has-[:checked]:border-primary has-[:checked]:ring-2 has-[:checked]:ring-primary/20 has-[:checked]:bg-primary/5';

                    const radio = document.createElement('input');
                    radio.type = 'radio';
                    radio.name = 'courier';
                    radio.value = (c.name || '') + '::' + (c.service || '');
                    radio.className = 'sr-only';
                    radio.dataset.cost = c.cost;
                    radio.checked = i === 0;
                    radio.addEventListener('change', () => {
                        selectedShippingCost = parseInt(radio.dataset.cost || '0') || 0;
                        refreshTotals();
                    });

                    const name = c.name || c.code;
                    const service = c.service || '';
                    const desc = c.description ? '<span class="block text-[11px] text-slate-500 mt-0.5">' + c.description + '</span>' : '';
                    const etd = c.etd ? '<span class="text-[11px] text-slate-400"> (' + c.etd + ')</span>' : '';
                    const costTxt = fmt(c.cost || 0);

                    label.innerHTML = `
                        <span class="w-5 h-5 rounded-full border-2 shrink-0 mt-0.5 flex items-center justify-center border-slate-300 has-[:checked]:border-primary has-[:checked]:[&>span]:bg-primary">
                            <span class="w-2.5 h-2.5 rounded-full bg-transparent"></span>
                        </span>
                        <span class="min-w-0 flex-1">
                            <span class="block font-display text-sm text-slate-900 uppercase">${name}</span>
                            <span class="block text-xs text-slate-700 font-medium">${service}${etd}</span>
                            ${desc}
                        </span>
                        <span class="font-semibold text-sm text-slate-900 shrink-0">${costTxt}</span>
                    `;
                    label.prepend(radio);
                    courierList.appendChild(label);
                });

                const first = courierList.querySelector('input[name="courier"]');
                if (first) {
                    selectedShippingCost = parseInt(first.dataset.cost || '0') || 0;
                }
                refreshTotals();
            }

            // Automatically open/load provinces on first focus.
            provinceCombo.el.querySelector('.combo-input').addEventListener('focus', () => provinceCombo.el.querySelector('.combo-input').click());
            cityCombo.el.querySelector('.combo-input').addEventListener('focus', () => {
                if (!cityCombo.el._parentId) return;
                cityCombo.el.querySelector('.combo-input').click();
            });
            districtCombo.el.querySelector('.combo-input').addEventListener('focus', () => {
                if (!districtCombo.el._parentId) return;
                districtCombo.el.querySelector('.combo-input').click();
            });
        });
    </script>
    @endpush
@endsection
