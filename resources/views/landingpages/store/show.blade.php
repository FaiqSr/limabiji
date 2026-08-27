{{-- resources/views/landingpages/store/show.blade.php --}}
@extends('layouts.store')

@php
    $locale          = app()->getLocale();
    $displayName     = $product->getNameForLocale($locale);
    $displayDesc     = $product->getDescriptionForLocale($locale);
    $displayDescription = $displayDesc; // alias kept for content section
    $tastingNotes    = $product->tasting_notes ?: [];
    $recommendedBrews = $product->recommended_brews ?: ['v60', 'espresso'];

    // SEO helpers
    $metaDesc        = Str::limit(strip_tags($displayDesc), 160);
    $productImage    = $product->image ?: asset('assets/images/limabiji/toko.webp');
    $canonicalUrl    = url()->current();
    $lowestPrice     = (int) $product->base_price_200g;
    $isInStock       = ($product->stock > 0);
    $availSchema     = $isInStock ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock';

    // Rich title: «Name — Roast · Process | Lima Biji Agritech»
    $roastLabel      = ucwords(str_replace('_', ' ', $product->roast_level ?? ''));
    $richTitle       = $displayName
        . ($roastLabel ? ' — ' . $roastLabel : '')
        . ($product->process ? ' · ' . $product->process : '')
        . ' | Lima Biji Agritech';

    // Build multi-Offer array (one per weight variant)
    $offerItems = [];
    foreach (['200g' => $product->base_price_200g, '500g' => $product->price_500g, '1kg' => $product->price_1kg] as $wt => $price) {
        if ((int) $price > 0) {
            $offerItems[] = [
                '@type'         => 'Offer',
                'name'          => $displayName . ' (' . $wt . ')',
                'url'           => $canonicalUrl,
                'priceCurrency' => 'IDR',
                'price'         => (int) $price,
                'priceValidUntil' => now()->addMonths(3)->toDateString(),
                'availability'  => $availSchema,
                'itemCondition' => 'https://schema.org/NewCondition',
                'seller' => [
                    '@type' => 'Organization',
                    'name'  => 'Lima Biji Agritech',
                    'url'   => url('/'),
                ],
            ];
        }
    }

    // Product schema node
    $productSchema = [
        '@type'       => 'Product',
        '@id'         => $canonicalUrl . '#product',
        'name'        => $displayName,
        'description' => strip_tags($displayDesc),
        'image'       => [$productImage],
        'sku'         => 'LB-' . str_pad($product->id, 4, '0', STR_PAD_LEFT),
        'brand'       => [
            '@type' => 'Brand',
            'name'  => 'Lima Biji Agritech',
        ],
        'category'    => ucfirst($product->category ?? 'Specialty Coffee'),
        'offers'      => $offerItems ?: [
            '@type'         => 'Offer',
            'url'           => $canonicalUrl,
            'priceCurrency' => 'IDR',
            'price'         => $lowestPrice,
            'availability'  => $availSchema,
            'itemCondition' => 'https://schema.org/NewCondition',
        ],
    ];

    // Optionally add aggregateRating from SCA score (mapped 0-100 to 1-5)
    if ($product->sca_score) {
        $ratingValue = round(($product->sca_score - 50) / 10, 1);
        $ratingValue = max(1.0, min(5.0, $ratingValue));
        $productSchema['aggregateRating'] = [
            '@type'       => 'AggregateRating',
            'ratingValue' => $ratingValue,
            'bestRating'  => 5,
            'worstRating' => 1,
            'ratingCount' => 1,
            'description' => 'SCA Cupping Score: ' . $product->sca_score . '/100',
        ];
    }

    // Optionally enrich with tasting notes as keywords
    if (!empty($tastingNotes)) {
        $productSchema['keywords'] = implode(', ', $tastingNotes);
    }
@endphp

@push('title', $richTitle)

@push('meta')
    {{-- Core --}}
    <meta name="description" content="{{ $metaDesc }}">
    <meta name="keywords" content="{{ implode(', ', array_filter([$displayName, $product->origin, $product->process, $roastLabel, 'specialty coffee', 'kopi specialty', 'Lima Biji'])) }}">
    <link rel="canonical" href="{{ $canonicalUrl }}">

    {{-- Open Graph — Product --}}
    <meta property="og:type" content="product">
    <meta property="og:title" content="{{ $richTitle }}">
    <meta property="og:description" content="{{ $metaDesc }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:image" content="{{ $productImage }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="{{ $displayName }} — Lima Biji Agritech">
    <meta property="product:price:amount" content="{{ $lowestPrice }}">
    <meta property="product:price:currency" content="IDR">
    <meta property="product:availability" content="{{ $isInStock ? 'in stock' : 'out of stock' }}">
    <meta property="product:condition" content="new">
    <meta property="product:brand" content="Lima Biji Agritech">
    @if ($product->category)
        <meta property="product:category" content="{{ ucfirst($product->category) }} Coffee">
    @endif

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $richTitle }}">
    <meta name="twitter:description" content="{{ $metaDesc }}">
    <meta name="twitter:image" content="{{ $productImage }}">
    <meta name="twitter:image:alt" content="{{ $displayName }} — Lima Biji Agritech">
    <meta name="twitter:label1" content="Price (from)">
    <meta name="twitter:data1" content="Rp {{ number_format($lowestPrice, 0, ',', '.') }}">
    <meta name="twitter:label2" content="Availability">
    <meta name="twitter:data2" content="{{ $isInStock ? 'In Stock' : 'Out of Stock' }}">
@endpush

@push('schema')
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@graph'   => [

                // 1. Organization (publisher)
                [
                    '@type'       => 'Organization',
                    '@id'         => url('/') . '#organization',
                    'name'        => 'Lima Biji Agritech',
                    'url'         => url('/'),
                    'logo'        => asset('favicon.ico'),
                    'description' => 'Specialty coffee producer & roaster specializing in enzymatic bio-fermentation processing from single-origin Indonesian farms.',
                    'contactPoint' => [
                        '@type'       => 'ContactPoint',
                        'contactType' => 'customer service',
                        'availableLanguage' => ['Indonesian', 'English'],
                    ],
                ],

                // 2. WebPage
                [
                    '@type'           => 'WebPage',
                    '@id'             => $canonicalUrl . '#webpage',
                    'url'             => $canonicalUrl,
                    'name'            => $richTitle,
                    'description'     => $metaDesc,
                    'inLanguage'      => $locale === 'id' ? 'id-ID' : 'en-US',
                    'isPartOf'        => ['@id' => url('/') . '#website'],
                    'breadcrumb'      => ['@id' => $canonicalUrl . '#breadcrumb'],
                    'about'           => ['@id' => $canonicalUrl . '#product'],
                    'primaryImageOfPage' => ['@type' => 'ImageObject', 'url' => $productImage],
                ],

                // 3. Product
                $productSchema,

                // 4. BreadcrumbList
                [
                    '@type' => 'BreadcrumbList',
                    '@id'   => $canonicalUrl . '#breadcrumb',
                    'itemListElement' => [
                        [
                            '@type'    => 'ListItem',
                            'position' => 1,
                            'name'     => $locale === 'id' ? 'Beranda' : 'Home',
                            'item'     => url('/'),
                        ],
                        [
                            '@type'    => 'ListItem',
                            'position' => 2,
                            'name'     => $locale === 'id' ? 'Toko Kopi' : 'Coffee Store',
                            'item'     => route('store.index'),
                        ],
                        [
                            '@type'    => 'ListItem',
                            'position' => 3,
                            'name'     => $displayName,
                            'item'     => $canonicalUrl,
                        ],
                    ],
                ],

            ],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>
@endpush

@section('content')
<div class="bg-[#fafaf9] text-slate-800 min-h-screen">
    <div class="container mx-auto px-5 lg:px-8 py-8 lg:py-12 max-w-7xl">

        {{-- Breadcrumbs & Back Navigation --}}
        <div class="flex items-center gap-2 text-xs sm:text-sm text-slate-500 mb-8">
            <a href="{{ route('store.index') }}" class="hover:text-primary transition-colors flex items-center gap-1.5 font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                {{ __('store.detail_back') }}
            </a>
            <span class="text-slate-300">/</span>
            <span class="text-slate-700 font-semibold truncate max-w-xs sm:max-w-md">{{ $displayName }}</span>
        </div>

        {{-- Main Product Split-Screen --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16 items-start mb-16">

            {{-- Left Column: Product Visual & Key Specs --}}
            <div class="lg:col-span-6 space-y-5">
                <div class="rounded-2xl overflow-hidden bg-white border border-slate-200 relative shadow-sm h-[380px] sm:h-[480px]">
                    <img src="{{ $product->image ?: asset('assets/images/limabiji/toko.webp') }}"
                        alt="{{ $displayName }}"
                        class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/50 via-transparent to-transparent"></div>

                    {{-- Badges on Image --}}
                    <div class="absolute top-4 left-4 flex flex-wrap gap-2">
                        <span class="px-3 py-1 rounded-xl bg-white/95 backdrop-blur-xs border border-slate-200 text-xs font-mono font-bold uppercase text-slate-800 shadow-sm">
                            {{ ucfirst($product->category) }}
                        </span>
                        @if ($product->sca_score)
                            <span class="px-3 py-1 rounded-xl bg-primary text-white text-xs font-mono font-bold uppercase shadow-sm">
                                {{ $product->sca_score }} SCA Score
                            </span>
                        @endif
                    </div>

                    <div class="absolute bottom-4 left-4 right-4 bg-white/95 backdrop-blur-xs p-4 rounded-xl border border-slate-200 flex items-center justify-between text-xs shadow-sm">
                        <span class="text-slate-600 font-mono">{{ $product->origin ?: 'Specialty Farm' }}</span>
                        <span class="text-primary font-semibold">{{ $product->altitude ?: '1400m MASL' }}</span>
                    </div>
                </div>

            </div>

            {{-- Right Column: Information, Live Variant Selector, and Actions --}}
            <div class="lg:col-span-6 space-y-6">
                <div>
                    <span class="text-xs font-mono font-bold uppercase tracking-widest text-primary block mb-2">
                        {{ ucfirst($product->category) }} • {{ $product->process }}
                    </span>
                    <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl text-slate-900 uppercase leading-[0.95] tracking-tight">
                        {{ $displayName }}
                    </h1>
                </div>

                {{-- Price Display (Dynamically updated via JS) --}}
                <div class="flex items-baseline gap-3 py-1">
                    <span id="product-live-price" class="font-display text-4xl sm:text-5xl text-slate-900">
                        {{ $product->getFormattedPrice('200g') }}
                    </span>
                    <span id="product-weight-label" class="text-xs text-slate-500 font-mono uppercase">/ 200g Pack</span>
                </div>

                {{-- Description --}}
                <p class="text-slate-600 text-sm sm:text-base leading-relaxed">
                    {{ $displayDescription }}
                </p>

                {{-- Tasting Notes Badges --}}
                @if (!empty($tastingNotes))
                    <div class="pt-1">
                        <h4 class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2.5">
                            {{ __('store.tasting_notes') }}
                        </h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($tastingNotes as $note)
                                <span class="px-3.5 py-1.5 rounded-xl bg-slate-100 border border-slate-200 text-xs font-semibold text-slate-800">
                                    {{ $note }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Spec Attributes Grid --}}
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 pt-1">
                    <div class="p-3.5 rounded-xl bg-white border border-slate-200 text-xs shadow-xs">
                        <p class="text-slate-400 uppercase text-[10px] font-mono">{{ __('store.spec_roast') }}</p>
                        <p class="font-bold text-slate-900 mt-0.5 capitalize">{{ str_replace('_', ' ', $product->roast_level) }}</p>
                    </div>
                    <div class="p-3.5 rounded-xl bg-white border border-slate-200 text-xs shadow-xs">
                        <p class="text-slate-400 uppercase text-[10px] font-mono">{{ __('store.spec_process') }}</p>
                        <p class="font-bold text-slate-900 mt-0.5">{{ $product->process }}</p>
                    </div>
                    <div class="p-3.5 rounded-xl bg-white border border-slate-200 text-xs shadow-xs col-span-2 sm:col-span-1">
                        <p class="text-slate-400 uppercase text-[10px] font-mono">{{ __('store.spec_altitude') }}</p>
                        <p class="font-bold text-slate-900 mt-0.5">{{ $product->altitude ?: 'Highland' }}</p>
                    </div>
                </div>

                {{-- Form: Interactive Variant Selector --}}
                <form id="product-order-form" class="space-y-6 pt-4 border-t border-slate-200">

                    {{-- 1. Package Size Selector --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-900 mb-3">
                            {{ __('store.select_weight') }}
                        </label>
                        <div class="grid grid-cols-3 gap-3">
                            @php
                                $weights = [
                                    '200g' => (int) $product->base_price_200g,
                                    '500g' => (int) $product->price_500g,
                                    '1kg'  => (int) $product->price_1kg,
                                ];
                            @endphp
                            @foreach ($weights as $weightKey => $priceVal)
                                <label class="cursor-pointer">
                                    <input type="radio" name="selected_weight" value="{{ $weightKey }}"
                                        data-price="{{ $priceVal }}"
                                        {{ $loop->first ? 'checked' : '' }}
                                        class="peer sr-only variant-weight-radio">
                                    <div class="p-3 rounded-xl border border-slate-200 bg-white text-center transition-all peer-checked:border-primary peer-checked:bg-emerald-50/60 peer-checked:text-primary hover:border-primary/40 shadow-xs">
                                        <p class="font-bold text-sm text-slate-900 peer-checked:text-primary">{{ $weightKey }}</p>
                                        <p class="text-[10px] text-slate-500 mt-0.5">Rp {{ number_format($priceVal, 0, ',', '.') }}</p>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- 2. Grind Size Selector --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-900 mb-3">
                            {{ __('store.select_grind') }}
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                            @php
                                $grindOptions = [
                                    'whole_bean' => ['label' => __('store.grind_whole_bean'), 'desc' => __('store.grind_whole_bean_desc')],
                                    'coarse'     => ['label' => __('store.grind_coarse'), 'desc' => __('store.grind_coarse_desc')],
                                    'medium'     => ['label' => __('store.grind_medium'), 'desc' => __('store.grind_medium_desc')],
                                    'fine'       => ['label' => __('store.grind_fine'), 'desc' => __('store.grind_fine_desc')],
                                ];
                            @endphp
                            @foreach ($grindOptions as $grindKey => $grindData)
                                <label class="cursor-pointer">
                                    <input type="radio" name="selected_grind" value="{{ $grindKey }}"
                                        {{ $loop->first ? 'checked' : '' }}
                                        class="peer sr-only variant-grind-radio">
                                    <div class="p-3 rounded-xl border border-slate-200 bg-white transition-all peer-checked:border-primary peer-checked:bg-emerald-50/60 hover:border-primary/40 shadow-xs">
                                        <p class="font-semibold text-xs text-slate-900 peer-checked:text-primary">{{ $grindData['label'] }}</p>
                                        <p class="text-[10px] text-slate-500 mt-0.5">{{ $grindData['desc'] }}</p>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- 3. Quantity Stepper & Add to Cart Buttons --}}
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 pt-2">
                        {{-- Quantity --}}
                        <div class="flex items-center justify-between sm:justify-start gap-3 bg-white border border-slate-200 rounded-xl px-4 py-2.5 shrink-0 shadow-xs">
                            <span class="text-xs font-semibold text-slate-500 uppercase mr-1">{{ __('store.quantity') }}</span>
                            <div class="flex items-center gap-2">
                                <button type="button" id="qty-minus" class="w-7 h-7 rounded-lg bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-700 hover:border-primary transition-colors active:scale-95 cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4"/></svg>
                                </button>
                                <input type="number" id="qty-input" value="1" min="1" max="50" class="w-10 text-center font-mono font-bold text-slate-900 bg-transparent focus:outline-none text-sm">
                                <button type="button" id="qty-plus" class="w-7 h-7 rounded-lg bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-700 hover:border-primary transition-colors active:scale-95 cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                </button>
                            </div>
                        </div>

                        {{-- Add to Cart --}}
                        <button type="button" id="btn-add-cart"
                            class="flex-1 px-6 py-3.5 rounded-xl bg-primary hover:bg-primary-hover active:scale-[0.98] text-white font-semibold text-xs sm:text-sm uppercase tracking-wider transition-all duration-200 shadow-md cursor-pointer flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            <span>{{ __('store.add_to_cart') }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Recommended Brewing Guide Card --}}
        <div class="bg-white p-6 sm:p-10 rounded-2xl mb-16 border border-slate-200 shadow-sm">
            <h3 class="font-display text-2xl sm:text-3xl text-slate-900 uppercase mb-4">{{ __('store.recommended_for') }}</h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                    <p class="text-xs font-mono font-bold text-primary uppercase">Water Temp</p>
                    <p class="font-display text-xl text-slate-900 mt-1">91°C to 93°C</p>
                    <p class="text-[11px] text-slate-500 mt-0.5">Optimizes enzymatic sweetness extraction</p>
                </div>
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                    <p class="text-xs font-mono font-bold text-primary uppercase">Brew Ratio</p>
                    <p class="font-display text-xl text-slate-900 mt-1">1 : 15 (Pour Over)</p>
                    <p class="text-[11px] text-slate-500 mt-0.5">15g coffee to 225g water</p>
                </div>
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                    <p class="text-xs font-mono font-bold text-primary uppercase">Resting Period</p>
                    <p class="font-display text-xl text-slate-900 mt-1">7 to 10 Days Degas</p>
                    <p class="text-[11px] text-slate-500 mt-0.5">Peak flavor clarity post-roast</p>
                </div>
            </div>
        </div>

        {{-- Related Products --}}
        @if ($relatedProducts->isNotEmpty())
            <div class="border-t border-slate-200 pt-14 pb-10">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="font-display text-3xl sm:text-4xl text-slate-900 uppercase">{{ __('store.related_title') }}</h3>
                    <a href="{{ route('store.index') }}" class="text-xs font-semibold text-primary hover:underline uppercase tracking-wider flex items-center gap-1">
                        <span>All Beans</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach ($relatedProducts as $relProduct)
                        <div class="bg-white rounded-2xl overflow-hidden p-5 border border-slate-200 hover:border-primary/50 transition-colors flex flex-col justify-between shadow-sm hover:shadow-md">
                            <div>
                                <img src="{{ $relProduct->image ?: asset('assets/images/limabiji/toko.webp') }}"
                                    alt="{{ $relProduct->getNameForLocale($locale) }}"
                                    class="w-full h-44 object-cover rounded-xl mb-4">
                                <span class="text-[10px] font-mono font-bold uppercase text-primary">{{ ucfirst($relProduct->category) }}</span>
                                <h4 class="font-display text-xl text-slate-900 uppercase mt-1 truncate">
                                    <a href="{{ route('store.show', $relProduct->slug) }}">{{ $relProduct->getNameForLocale($locale) }}</a>
                                </h4>
                            </div>
                            <div class="flex items-center justify-between pt-4 mt-4 border-t border-slate-100">
                                <span class="font-display text-lg text-slate-900">{{ $relProduct->getFormattedPrice('200g') }}</span>
                                <a href="{{ route('store.show', $relProduct->slug) }}" class="text-xs font-semibold text-primary hover:underline flex items-center gap-1">
                                    <span>View</span>
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const priceDisplay = document.getElementById('product-live-price');
            const weightLabel = document.getElementById('product-weight-label');
            const weightRadios = document.querySelectorAll('.variant-weight-radio');
            const qtyInput = document.getElementById('qty-input');
            const qtyMinus = document.getElementById('qty-minus');
            const qtyPlus = document.getElementById('qty-plus');
            const btnAddCart = document.getElementById('btn-add-cart');

            function updatePrice() {
                const selected = document.querySelector('.variant-weight-radio:checked');
                if (selected && priceDisplay && weightLabel) {
                    const price = Number(selected.getAttribute('data-price') || 0);
                    priceDisplay.textContent = 'Rp ' + price.toLocaleString('id-ID');
                    weightLabel.textContent = '/ ' + selected.value + ' Pack';
                }
            }

            weightRadios.forEach(radio => radio.addEventListener('change', updatePrice));

            if (qtyMinus && qtyPlus && qtyInput) {
                qtyMinus.addEventListener('click', () => {
                    const val = Math.max(1, parseInt(qtyInput.value || '1') - 1);
                    qtyInput.value = val;
                });
                qtyPlus.addEventListener('click', () => {
                    const val = Math.min(50, parseInt(qtyInput.value || '1') + 1);
                    qtyInput.value = val;
                });
            }

            if (btnAddCart) {
                btnAddCart.addEventListener('click', () => {
                    const weight = document.querySelector('.variant-weight-radio:checked')?.value || '200g';
                    const grind = document.querySelector('.variant-grind-radio:checked')?.value || 'whole_bean';
                    const qty = parseInt(qtyInput?.value || '1');

                    window.LimaBijiCart.addItem({{ $product->id }}, weight, grind, qty);
                });
            }
        });
    </script>
    @endpush
@endsection
