{{-- resources/views/landingpages/store/index.blade.php --}}
@extends('layouts.store')

@php
    $locale    = app()->getLocale();
    $pageTitle = $locale === 'id'
        ? 'Toko Kopi Specialty | Lima Biji Agritech — Arabica, Robusta & Blend'
        : 'Specialty Coffee Store | Lima Biji Agritech — Arabica, Robusta & Blend';
    $metaDesc  = $locale === 'id'
        ? 'Beli kopi specialty single origin Indonesia: Arabica, Robusta Fine, Blend, dan Eksperimental. Diproses bio-fermentasi enzimatik, dikirim fresh-roasted ke seluruh Indonesia.'
        : 'Buy specialty single-origin Indonesian coffee: Arabica, Fine Robusta, Blends, and Experimental. Enzymatic bio-fermentation processed, fresh-roasted and shipped nationwide.';
    $ogImage   = asset('assets/images/limabiji/toko.webp');
    $canonical = url()->current();
    $storeUrl  = route('store.index');
@endphp

@push('title', $pageTitle)

@push('meta')
    {{-- Core --}}
    <meta name="description" content="{{ $metaDesc }}">
    <meta name="keywords" content="specialty coffee indonesia, kopi specialty, arabica indonesia, robusta fine, kopi luwak enzimatik, lima biji agritech, biji kopi sca, beli kopi online, kopi specialty jakarta">
    <link rel="canonical" href="{{ $canonical }}">

    {{-- Open Graph --}}
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $metaDesc }}">
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="Lima Biji Agritech — Specialty Coffee Store">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $metaDesc }}">
    <meta name="twitter:image" content="{{ $ogImage }}">
    <meta name="twitter:image:alt" content="Lima Biji Agritech — Specialty Coffee Store">
@endpush

@push('schema')
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@graph'   => array_filter([

                // 1. WebSite with SearchAction
                [
                    '@type'           => 'WebSite',
                    '@id'             => url('/') . '#website',
                    'url'             => url('/'),
                    'name'            => 'Lima Biji Agritech',
                    'description'     => 'Specialty coffee producer & roaster — single-origin Indonesian beans.',
                    'inLanguage'      => $locale === 'id' ? 'id-ID' : 'en-US',
                    'publisher'       => ['@id' => url('/') . '#organization'],
                    'potentialAction' => [
                        '@type'       => 'SearchAction',
                        'target'      => [
                            '@type'       => 'EntryPoint',
                            'urlTemplate' => route('store.index') . '?q={search_term_string}',
                        ],
                        'query-input' => 'required name=search_term_string',
                    ],
                ],

                // 2. Organization
                [
                    '@type'       => 'Organization',
                    '@id'         => url('/') . '#organization',
                    'name'        => 'Lima Biji Agritech',
                    'url'         => url('/'),
                    'logo'        => [
                        '@type' => 'ImageObject',
                        'url'   => asset('favicon.ico'),
                    ],
                    'description' => 'Specialty coffee producer & roaster specializing in enzymatic bio-fermentation processing from single-origin Indonesian farms.',
                    'contactPoint' => [
                        '@type'       => 'ContactPoint',
                        'contactType' => 'customer service',
                        'availableLanguage' => ['Indonesian', 'English'],
                    ],
                ],

                // 3. CollectionPage
                [
                    '@type'       => 'CollectionPage',
                    '@id'         => $canonical . '#webpage',
                    'url'         => $canonical,
                    'name'        => $pageTitle,
                    'description' => $metaDesc,
                    'inLanguage'  => $locale === 'id' ? 'id-ID' : 'en-US',
                    'isPartOf'    => ['@id' => url('/') . '#website'],
                    'publisher'   => ['@id' => url('/') . '#organization'],
                    'breadcrumb'  => ['@id' => $canonical . '#breadcrumb'],
                    'primaryImageOfPage' => [
                        '@type' => 'ImageObject',
                        'url'   => $ogImage,
                    ],
                ],

                // 4. ItemList — catalog snapshot (max 10 to keep payload lean)
                $products->isNotEmpty() ? [
                    '@type'           => 'ItemList',
                    '@id'             => $canonical . '#product-list',
                    'name'            => $locale === 'id' ? 'Katalog Kopi Specialty' : 'Specialty Coffee Catalog',
                    'numberOfItems'   => $products->total(),
                    'itemListElement' => $products->take(10)->values()->map(function ($p, $i) use ($locale) {
                        return [
                            '@type'    => 'ListItem',
                            'position' => $i + 1,
                            'url'      => route('store.show', $p->slug),
                            'name'     => $p->getNameForLocale($locale),
                            'item'     => [
                                '@type'    => 'Product',
                                'name'     => $p->getNameForLocale($locale),
                                'image'    => $p->image ?: asset('assets/images/limabiji/toko.webp'),
                                'sku'      => 'LB-' . str_pad($p->id, 4, '0', STR_PAD_LEFT),
                                'category' => ucfirst($p->category ?? 'Specialty Coffee'),
                                'brand'    => ['@type' => 'Brand', 'name' => 'Lima Biji Agritech'],
                                'offers'   => [
                                    '@type'         => 'Offer',
                                    'priceCurrency' => 'IDR',
                                    'price'         => (int) $p->base_price_200g,
                                    'availability'  => $p->stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                                    'url'           => route('store.show', $p->slug),
                                ],
                            ],
                        ];
                    })->all(),
                ] : null,

                // 5. BreadcrumbList
                [
                    '@type' => 'BreadcrumbList',
                    '@id'   => $canonical . '#breadcrumb',
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
                            'item'     => $canonical,
                        ],
                    ],
                ],

            ]),
        ], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>
@endpush

@section('content')
<div class="bg-[#fafaf9] text-slate-800 min-h-screen">
    {{-- 1. Hero Section: Viewport Calibrated --}}
    <div class="container mx-auto px-5 lg:px-8 pt-8 pb-10 lg:pt-12 lg:pb-14 max-w-7xl">
        <div class="max-w-4xl">
            <p class="text-xs sm:text-sm font-bold tracking-[0.25em] uppercase text-primary mb-3">
                {{ __('store.hero_label') }}
            </p>
            <h1 class="font-display text-5xl sm:text-7xl lg:text-8xl text-slate-900 leading-[0.9] uppercase tracking-tight">
                {!! __('store.hero_heading') !!}
            </h1>
            <p class="text-slate-600 text-base sm:text-lg mt-5 max-w-2xl leading-relaxed">
                {{ __('store.hero_subheading') }}
            </p>
        </div>
    </div>

    {{-- 2. Interactive Coffee Finder Quiz Banner (Clean Bento Light) --}}
    <div class="container mx-auto px-5 lg:px-8 mb-10 max-w-7xl">
        <div class="p-6 sm:p-10 rounded-2xl relative overflow-hidden bg-gradient-to-r from-white via-emerald-50/40 to-white border border-emerald-200/80 shadow-sm">
            <div class="absolute -right-6 -bottom-8 opacity-5 pointer-events-none select-none text-primary">
                <span class="font-display text-[10rem] leading-none">QUIZ</span>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center relative z-10">
                <div class="lg:col-span-8 space-y-2.5">
                    <h2 class="font-display text-2xl sm:text-4xl text-slate-900 uppercase leading-tight">
                        {{ __('store.quiz_banner_title') }}
                    </h2>
                    <p class="text-slate-600 text-xs sm:text-sm leading-relaxed max-w-2xl">
                        {{ __('store.quiz_banner_desc') }}
                    </p>
                </div>
                <div class="lg:col-span-4 lg:text-right">
                    <button type="button" data-open-quiz
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-primary hover:bg-primary-hover active:scale-[0.98] text-white font-semibold text-xs sm:text-sm uppercase tracking-wider transition-colors shadow-md cursor-pointer">
                        <span>{{ __('store.quiz_banner_btn') }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. Catalog Section: Filters, Search & Grid --}}
    <div class="container mx-auto px-5 lg:px-8 pb-20 max-w-7xl">

        {{-- Filter & Search Toolbar --}}
        <div class="flex flex-col lg:flex-row justify-between items-stretch lg:items-center gap-4 mb-8 pb-6 border-b border-slate-200">

            {{-- Category Filter Pills --}}
            <div class="flex items-center gap-2 overflow-x-auto pb-2 lg:pb-0 scrollbar-none">
                @php
                    $categories = [
                        'all' => __('store.filter_all'),
                        'arabika' => __('store.filter_arabika'),
                        'robusta' => __('store.filter_robusta'),
                        'blend' => __('store.filter_blend'),
                        'experimental' => __('store.filter_experimental'),
                    ];
                @endphp

                @foreach ($categories as $catKey => $catLabel)
                    @php
                        $isActive = ($category === $catKey);
                        $params = request()->query();
                        $params['category'] = $catKey;
                        $url = route('store.index', $params);
                    @endphp
                    <a href="{{ $url }}"
                        class="px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold transition-all duration-200 shrink-0 active:scale-95
                        {{ $isActive
                            ? 'bg-primary text-white shadow-sm border border-primary'
                            : 'bg-white border border-slate-200 text-slate-700 hover:border-primary/50 hover:text-primary' }}">
                        {{ $catLabel }}
                    </a>
                @endforeach
            </div>

            {{-- Search & Sort Form --}}
            <form method="GET" action="{{ route('store.index') }}" class="flex flex-col sm:flex-row items-center gap-3">
                @if ($category && $category !== 'all')
                    <input type="hidden" name="category" value="{{ $category }}">
                @endif

                {{-- Search Box --}}
                <div class="relative w-full sm:w-64">
                    <input type="text" name="q" value="{{ $search }}"
                        placeholder="{{ __('store.search_placeholder') }}"
                        class="w-full px-4 py-2 pl-9 text-xs sm:text-sm bg-white border border-slate-200 rounded-xl text-slate-900 placeholder:text-slate-400 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary shadow-xs transition-colors">
                    <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                {{-- Sort Selector --}}
                <div class="relative w-full sm:w-48">
                    <select name="sort" onchange="this.form.submit()"
                        class="w-full px-3 py-2 text-xs sm:text-sm bg-white border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:border-primary shadow-xs transition-colors">
                        <option value="featured" {{ $sort === 'featured' ? 'selected' : '' }}>{{ __('store.sort_featured') }}</option>
                        <option value="price_asc" {{ $sort === 'price_asc' ? 'selected' : '' }}>{{ __('store.sort_price_asc') }}</option>
                        <option value="price_desc" {{ $sort === 'price_desc' ? 'selected' : '' }}>{{ __('store.sort_price_desc') }}</option>
                        <option value="sca_desc" {{ $sort === 'sca_desc' ? 'selected' : '' }}>{{ __('store.sort_sca_desc') }}</option>
                        <option value="name_asc" {{ $sort === 'name_asc' ? 'selected' : '' }}>{{ __('store.sort_name_asc') }}</option>
                    </select>
                </div>
            </form>
        </div>

        {{-- Products Grid --}}
        @if ($products->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-5">
                @foreach ($products as $product)
                    @php
                        $displayName = $product->getNameForLocale($locale);
                        $tastingNotes = $product->tasting_notes ?: [];
                    @endphp
                    <div class="bg-white rounded-2xl overflow-hidden border border-slate-200/90 hover:border-primary/50 transition-colors flex flex-col group shadow-sm hover:shadow-md">

                        {{-- Product Thumbnail Image --}}
                        <div class="relative h-40 sm:h-48 bg-slate-100 overflow-hidden">
                            <img src="{{ $product->image ?: asset('assets/images/limabiji/toko.webp') }}"
                                alt="{{ $displayName }}"
                                loading="lazy" decoding="async"
                                class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/40 via-transparent to-transparent"></div>

                            {{-- Category & SCA Score Badges --}}
                            <div class="absolute top-2 left-2 flex flex-wrap gap-1.5">
                                <span class="px-2 py-0.5 rounded-md bg-white/95 backdrop-blur-xs border border-slate-200 text-[9px] font-mono font-bold uppercase text-slate-800 shadow-xs">
                                    {{ ucfirst($product->category) }}
                                </span>
                                @if ($product->sca_score)
                                    <span class="px-2 py-0.5 rounded-md bg-primary/95 backdrop-blur-xs text-[9px] font-mono font-bold uppercase text-white shadow-xs">
                                        {{ $product->sca_score }} SCA
                                    </span>
                                @endif
                            </div>

                            @if ($product->is_featured)
                                <div class="absolute top-2 right-2">
                                    <span class="px-2 py-0.5 rounded-md bg-secondary text-white text-[9px] font-mono font-bold uppercase shadow-xs">
                                        Signature
                                    </span>
                                </div>
                            @endif

                            <div class="absolute bottom-2 left-2 right-2">
                                <span class="text-[10px] text-white font-mono flex items-center gap-1.5 drop-shadow-sm truncate">
                                    <svg class="w-3 h-3 text-emerald-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    </svg>
                                    {{ $product->origin ?: 'Specialty Micro Lot' }}
                                </span>
                            </div>
                        </div>

                        {{-- Card Content --}}
                        <div class="p-4 flex-1 flex flex-col justify-between space-y-3">
                            <div>
                                <h3 class="font-display text-lg sm:text-xl text-slate-900 uppercase tracking-tight group-hover:text-primary transition-colors line-clamp-1">
                                    <a href="{{ route('store.show', $product->slug) }}">{{ $displayName }}</a>
                                </h3>

                                <p class="text-slate-600 text-xs line-clamp-2 mt-1.5 leading-relaxed">
                                    {{ $product->getDescriptionForLocale($locale) }}
                                </p>

                                {{-- Tasting Notes Tags --}}
                                @if (!empty($tastingNotes))
                                    <div class="flex flex-wrap gap-1.5 mt-2.5">
                                        @foreach (array_slice($tastingNotes, 0, 3) as $note)
                                            <span class="px-1.5 py-0.5 rounded-md bg-slate-100 border border-slate-200/80 text-[9px] font-medium text-slate-700">
                                                {{ $note }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            {{-- Price & Actions --}}
                            <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-3 mt-auto">
                                <div>
                                    <p class="text-[9px] text-slate-400 uppercase font-mono tracking-wider">Starts from</p>
                                    <p class="font-display text-xl text-slate-900 leading-none mt-0.5">
                                        {{ $product->getFormattedPrice('200g') }}
                                    </p>
                                </div>

                                <div class="flex items-center gap-1.5">
                                    <a href="{{ route('store.show', $product->slug) }}"
                                        class="p-2 rounded-lg bg-slate-100 hover:bg-slate-200 border border-slate-200 text-slate-600 hover:text-slate-900 transition-colors"
                                        title="{{ __('store.view_detail') }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>

                                    <button type="button"
                                        onclick="LimaBijiCart.addItem({{ $product->id }}, '200g', 'whole_bean', 1)"
                                        class="px-3.5 py-2 rounded-lg bg-primary hover:bg-primary-hover active:scale-[0.98] text-white text-[11px] font-semibold uppercase tracking-wider transition-all duration-200 shadow-sm cursor-pointer flex items-center gap-1.5">
                                        <span>+ Cart</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination Links --}}
            @if ($products->hasPages())
                <div class="mt-12 flex flex-col">
                    {{ $products->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-20 bg-white border border-slate-200 rounded-2xl shadow-xs">
                <div class="w-16 h-16 rounded-2xl bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400 mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h4 class="font-display text-2xl text-slate-900 uppercase">{{ __('store.no_products_found') }}</h4>
                <a href="{{ route('store.index') }}" class="inline-block mt-4 text-xs font-semibold text-primary hover:underline uppercase tracking-wider">
                    Reset Filter
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
