{{-- resources/views/landingpages/store/tracking.blade.php --}}
@extends('layouts.store')

@php
    $locale = app()->getLocale();

    $pageTitle = $locale === 'id'
        ? 'Lacak Pesanan Kopi | Lima Biji Agritech — Toko Kopi Specialty'
        : 'Track Your Order | Lima Biji Agritech — Specialty Coffee Store';
    $metaDesc = __('store.tracking_desc');
    $ogImage = asset('assets/images/limabiji/toko.webp');
    $canonical = route('store.tracking');
    $pageUrl = url()->full();
@endphp

@push('title', $pageTitle)

@push('meta')
    {{-- Core --}}
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta name="description" content="{{ $metaDesc }}">
    <meta name="keywords" content="lacak pesanan kopi, track coffee order, nomor resi lima biji, cek status pesanan kopi, lima biji tracking, resi kopi specialty, order tracking indonesia">
    <link rel="canonical" href="{{ $canonical }}">

    {{-- Open Graph --}}
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $metaDesc }}">
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="{{ $pageTitle }}">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $metaDesc }}">
    <meta name="twitter:image" content="{{ $ogImage }}">
    <meta name="twitter:image:alt" content="{{ $pageTitle }}">
@endpush

@push('schema')
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',            '@graph' => array_filter([

                // 1. WebSite with SearchAction for the tracking lookup
                [
                    '@type' => 'WebSite',
                    '@id' => url('/') . '#website',
                    'url' => url('/'),
                    'name' => 'Lima Biji Agritech',
                    'description' => 'Specialty coffee producer & roaster — single-origin Indonesian beans.',
                    'inLanguage' => $locale === 'id' ? 'id-ID' : 'en-US',
                    'publisher' => ['@id' => url('/') . '#organization'],
                    'potentialAction' => [
                        '@type' => 'SearchAction',
                        'target' => [
                            '@type' => 'EntryPoint',
                            'urlTemplate' => route('store.tracking') . '?order={order_number}',
                        ],
                        'query-input' => 'required name=order_number',
                    ],
                ],

                // 2. Organization
                [
                    '@type' => 'Organization',
                    '@id' => url('/') . '#organization',
                    'name' => 'Lima Biji Agritech',
                    'url' => url('/'),
                    'logo' => ['@type' => 'ImageObject', 'url' => asset('favicon.ico')],
                    'description' => 'Specialty coffee producer & roaster specializing in enzymatic bio-fermentation processing from single-origin Indonesian farms.',
                ],

                // 3. WebPage
                [
                    '@type' => 'WebPage',
                    '@id' => $canonical . '#webpage',
                    'url' => $canonical,
                    'name' => $pageTitle,
                    'description' => $metaDesc,
                    'inLanguage' => $locale === 'id' ? 'id-ID' : 'en-US',
                    'isPartOf' => ['@id' => url('/') . '#website'],
                    'publisher' => ['@id' => url('/') . '#organization'],
                ],

                // 4. BreadcrumbList
                [
                    '@type' => 'BreadcrumbList',
                    'itemListElement' => [
                        ['@type' => 'ListItem', 'position' => 1, 'name' => __('nav.home'), 'item' => url('/')],
                        ['@type' => 'ListItem', 'position' => 2, 'name' => __('store.detail_back'), 'item' => route('store.index')],
                        ['@type' => 'ListItem', 'position' => 3, 'name' => $pageTitle, 'item' => $canonical],
                    ],
                ],

                // 5. Order schema when a lookup succeeds (no PII)
                $order && $order->exists ? [
                    '@type' => 'Order',
                    '@id' => $pageUrl . '#order',
                    'orderNumber' => $order->order_number,
                    'orderStatus' => match ($order->shipping_status) {
                        'delivered' => 'https://schema.org/OrderDelivered',
                        'shipped' => 'https://schema.org/OrderInTransit',
                        'processing' => 'https://schema.org/OrderProcessing',
                        'cancelled' => 'https://schema.org/OrderCancelled',
                        default => $order->payment_status === 'paid'
                            ? 'https://schema.org/OrderProcessing'
                            : 'https://schema.org/OrderPaymentDue',
                    },
                    'orderDate' => $order->created_at?->toIso8601String(),
                    'priceCurrency' => 'IDR',
                    'price' => (string) $order->total_amount,
                    'acceptedOffer' => [
                        '@type' => 'Offer',
                        'priceCurrency' => 'IDR',
                        'price' => (string) $order->total_amount,
                        'itemOffered' => [
                            '@type' => 'Product',
                            'name' => 'Lima Biji Specialty Coffee',
                            'description' => $order->items->pluck('product_name')->implode(', '),
                        ],
                    ],
                    'merchant' => ['@id' => url('/') . '#organization'],
                ] : null,
            ], fn ($node) => $node !== null),
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endpush

@section('content')
@php
    $found = $order !== null;

    $steps = [];
    if ($found) {
        $shipping = $order->shipping_status;
        $steps = [
            [
                'label' => __('store.tracking_step_ordered'),
                'reached' => true,
                'date' => $order->created_at?->format('d F Y, H:i'),
            ],
            [
                'label' => __('store.tracking_step_payment'),
                'reached' => $order->payment_status === 'paid',
                'date' => $order->paid_at?->format('d F Y, H:i'),
            ],
            [
                'label' => __('store.tracking_step_packed'),
                'reached' => in_array($shipping, ['processing', 'shipped', 'delivered'], true),
                'date' => null,
            ],
            [
                'label' => __('store.tracking_step_shipped'),
                'reached' => in_array($shipping, ['shipped', 'delivered'], true),
                'date' => $order->shipped_at?->format('d F Y, H:i'),
            ],
            [
                'label' => __('store.tracking_step_delivered'),
                'reached' => $shipping === 'delivered',
                'date' => $order->delivered_at?->format('d F Y, H:i'),
            ],
        ];

        $currentIndex = 0;
        foreach ($steps as $i => $s) {
            if ($s['reached']) {
                $currentIndex = $i;
            }
        }
    }
@endphp

<div class="bg-[#fafaf9] text-slate-800 min-h-[100dvh]">
    <div class="container mx-auto px-5 lg:px-10 py-10 lg:py-16 max-w-6xl">

        {{-- Masthead --}}
        <header class="max-w-2xl animate-rise" style="animation-delay: 0ms">
            <div class="flex items-center gap-2 text-xs sm:text-sm text-slate-500 mb-4">
                <a href="{{ route('store.index') }}" class="hover:text-primary transition-colors flex items-center gap-1.5 font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    {{ __('store.detail_back') }}
                </a>
                <span class="text-slate-300">/</span>
                <span class="text-slate-700 font-semibold">{{ __('store.tracking_title') }}</span>
            </div>

            <p class="text-[11px] font-mono font-bold uppercase tracking-widest text-primary mb-2">{{ __('store.tracking_kicker') }}</p>
            <h1 class="font-display text-4xl sm:text-5xl text-slate-900 uppercase leading-none tracking-tight">{{ __('store.tracking_title') }}</h1>
            <p class="mt-4 text-sm sm:text-base text-slate-600 leading-relaxed max-w-[65ch]">{{ __('store.tracking_desc') }}</p>
        </header>

        {{-- Search --}}
        <section class="mt-8 lg:mt-10 animate-rise" style="animation-delay: 80ms">
            <form method="GET" action="{{ route('store.tracking') }}" class="bg-white rounded-2xl border border-slate-200 shadow-[0_20px_40px_-20px_rgba(0,0,0,0.06)] p-6 sm:p-8">
                <label for="order" class="block text-slate-800 font-semibold text-xs uppercase tracking-wider mb-2.5">
                    {{ __('store.tracking_enter_label') }}
                </label>
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="relative flex-1">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </span>
                        <input type="text" name="order" id="order" value="{{ $query }}"
                            placeholder="{{ __('store.tracking_placeholder') }}"
                            class="w-full bg-white border border-slate-200 rounded-xl py-3.5 pl-12 pr-4 text-sm font-mono font-semibold tracking-wider uppercase text-slate-900 placeholder:text-slate-300 placeholder:normal-case placeholder:font-sans placeholder:font-normal placeholder:tracking-normal focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors shadow-xs"
                            autocomplete="off">
                    </div>
                    <button type="submit"
                        class="sm:w-auto px-8 py-3.5 rounded-xl bg-primary hover:bg-primary-hover active:scale-[0.98] text-white font-semibold text-xs sm:text-sm uppercase tracking-wider transition-all duration-200 shadow-sm cursor-pointer inline-flex items-center justify-center gap-2 shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                        {{ __('store.tracking_btn') }}
                    </button>
                </div>
                <p class="mt-3 text-[11px] text-slate-400 leading-relaxed">{{ __('store.tracking_hint') }}</p>
            </form>
        </section>

        {{-- Result: not found --}}
        @if ($query !== '' && ! $found)
            <section class="mt-8 animate-rise" style="animation-delay: 160ms">
                <div class="rounded-2xl border border-rose-200 bg-rose-50/60 px-6 sm:px-8 py-6">
                    <div class="flex items-start gap-4">
                        <span class="w-10 h-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center shrink-0 border border-rose-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </span>
                        <div class="min-w-0">
                            <h2 class="font-display text-xl sm:text-2xl text-slate-900 uppercase">{{ __('store.tracking_not_found_title') }}</h2>
                            <p class="text-sm text-slate-600 leading-relaxed max-w-[60ch] mt-1">{{ __('store.tracking_not_found_desc') }}</p>
                            <div class="pt-3">
                                <a href="{{ url('/contact') }}" class="text-xs font-semibold text-primary hover:underline uppercase tracking-wider inline-flex items-center gap-1.5">
                                    {{ __('store.order_contact_support') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        {{-- Result: found --}}
        @if ($found)
            <div class="mt-10 grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 items-start">

                {{-- LEFT: status timeline --}}
                <section class="lg:col-span-7 min-w-0 animate-rise" style="animation-delay: 160ms">
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-[0_20px_40px_-20px_rgba(0,0,0,0.06)] overflow-hidden">
                        <div class="px-6 sm:px-8 py-5 border-b border-slate-100 flex items-center justify-between gap-3">
                            <div>
                                <p class="text-[11px] font-mono font-bold uppercase tracking-widest text-slate-400">{{ __('store.tracking_result_title') }}</p>
                                <p class="mt-1 font-mono font-bold text-slate-900 uppercase tracking-wider">{{ $order->order_number }}</p>
                            </div>
                            <span class="text-[10px] font-mono font-bold px-2 py-1 rounded-full bg-emerald-100 text-emerald-800 uppercase tracking-wider">{{ $steps[$currentIndex]['label'] }}</span>
                        </div>

                        <div class="px-6 sm:px-8 py-8">
                            <ol class="relative">
                                @foreach ($steps as $i => $step)
                                    <li class="relative flex gap-4 pb-8 last:pb-0">
                                        @unless ($loop->last)
                                            <span class="absolute left-[11px] top-8 bottom-0 w-px {{ $step['reached'] && $steps[$i + 1]['reached'] ? 'bg-emerald-300' : 'bg-slate-200' }}"></span>
                                        @endunless

                                        <span class="relative z-10 mt-0.5 shrink-0">
                                            @if ($i === $currentIndex && $step['reached'])
                                                <span class="absolute -inset-1.5 rounded-full bg-emerald-100 animate-pulse-dot"></span>
                                            @endif
                                            <span class="relative flex w-[23px] h-[23px] rounded-full border-2 items-center justify-center
                                                {{ $step['reached'] ? 'border-emerald-500 bg-emerald-500 text-white' : 'border-slate-300 bg-white' }}">
                                                @if ($step['reached'])
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                                @endif
                                            </span>
                                        </span>

                                        <div class="min-w-0 pt-0.5">
                                            <p class="text-sm font-semibold {{ $step['reached'] ? 'text-slate-900' : 'text-slate-400' }}">{{ $step['label'] }}</p>
                                            @if ($i === $currentIndex && $step['reached'])
                                                <span class="inline-flex items-center gap-1 mt-1 px-2 py-0.5 rounded text-[10px] font-bold uppercase font-mono tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                    @if ($i === count($steps) - 1)
                                                        {{ __('store.tracking_done') }}
                                                    @else
                                                        {{ __('store.tracking_current') }}
                                                    @endif
                                                </span>
                                            @endif
                                            @if ($step['date'])
                                                <p class="text-xs text-slate-400 font-mono mt-1">{{ $step['date'] }}</p>
                                            @endif
                                            @if ($i === 3 && $order->tracking_number)
                                                <p class="text-xs text-slate-500 mt-1 font-mono">{{ __('store.tracking_resi') }}: <span class="font-bold text-slate-800 select-all">{{ $order->tracking_number }}</span></p>
                                            @endif
                                        </div>
                                    </li>
                                @endforeach
                            </ol>
                        </div>
                    </div>
                </section>

                {{-- RIGHT: order summary --}}
                <aside class="lg:col-span-5 min-w-0 lg:sticky lg:top-24 animate-rise" style="animation-delay: 240ms">
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-[0_20px_40px_-20px_rgba(0,0,0,0.06)] overflow-hidden">

                        <div class="px-6 sm:px-8 py-5 border-b border-slate-100">
                            <div class="flex items-center justify-between gap-2 flex-wrap">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-mono font-bold uppercase tracking-wider {{ $order->getPaymentStatusBadgeClass() }}">
                                    {{ $order->getPaymentStatusLabel() }}
                                </span>
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-mono font-bold uppercase tracking-wider {{ $order->getShippingStatusBadgeClass() }}">
                                    {{ $order->getShippingStatusLabel() }}
                                </span>
                            </div>
                            <div class="mt-5 space-y-1.5 text-xs text-slate-500">
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
                            <div class="divide-y divide-slate-100 max-h-56 overflow-y-auto pr-1">
                                @foreach ($order->items as $item)
                                    <div class="py-3 flex items-start justify-between gap-4">
                                        <div class="min-w-0">
                                            <p class="font-display text-sm text-slate-900 uppercase leading-tight truncate">{{ $item->product_name }}</p>
                                            <p class="text-slate-500 text-[11px] mt-0.5">{{ $item->weight }} <span class="text-slate-300">/</span> {{ ucfirst(str_replace('_', ' ', $item->grind_size)) }} <span class="text-slate-300">/</span> {{ __('store.order_qty') }}: {{ $item->quantity }}</p>
                                        </div>
                                        <span class="font-mono font-semibold text-slate-900 shrink-0">{{ $item->getFormattedSubtotal() }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="px-6 sm:px-8 py-5 border-b border-slate-100 text-xs sm:text-sm">
                            <p class="text-[11px] font-mono font-bold uppercase tracking-widest text-slate-400 mb-2">{{ __('store.tracking_courier') }}</p>
                            <p class="font-semibold text-slate-900">{{ $order->courier }}</p>
                            @if ($order->tracking_number)
                                <p class="text-slate-500 mt-1 font-mono">{{ __('store.tracking_resi') }}: <span class="font-bold text-slate-800 select-all">{{ $order->tracking_number }}</span></p>
                            @endif
                            @if ($order->city)
                                <p class="text-slate-500 mt-1">{{ $order->city }}</p>
                            @endif
                        </div>

                        <div class="px-6 sm:px-8 py-5 space-y-3">
                            <a href="{{ route('store.order.status', $order->order_number) }}"
                                class="w-full flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-primary hover:bg-primary-hover active:scale-[0.98] text-white text-xs font-semibold uppercase tracking-wider transition-all duration-200 shadow-sm cursor-pointer">
                                {{ __('store.tracking_open_status') }}
                            </a>
                            <a href="{{ route('store.index') }}"
                                class="w-full flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 text-slate-800 text-xs font-semibold uppercase tracking-wider transition-colors">
                                {{ __('store.order_browse_more') }}
                            </a>
                        </div>
                    </div>
                </aside>
            </div>
        @endif
    </div>
</div>
@endsection
