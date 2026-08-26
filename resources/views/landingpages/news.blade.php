@extends('layouts.landingpages')

@php
    $locale = app()->getLocale();
    $selectedCategory = request()->get('category', 'All');
    $searchQuery = request()->get('q', '');
@endphp

@push('title', ($locale === 'id' ? 'Perpustakaan Berita' : 'News Library') . ' — Lima Biji Agritech')

@push('meta')
    <meta name="description" content="{{ __('landing.news_library_subheading') }}">
    <meta property="og:title" content="{{ ($locale === 'id' ? 'Perpustakaan Berita' : 'News Library') . ' — Lima Biji Agritech' }}">
    <meta property="og:description" content="{{ __('landing.news_library_subheading') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('favicon.ico') }}">
    <meta name="twitter:title" content="{{ ($locale === 'id' ? 'Perpustakaan Berita' : 'News Library') . ' — Lima Biji Agritech' }}">
    <meta name="twitter:description" content="{{ __('landing.news_library_subheading') }}">
    <meta name="twitter:image" content="{{ asset('favicon.ico') }}">
    <link rel="canonical" href="{{ url()->current() }}">
@endpush

@push('schema')
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => 'CollectionPage',
                    '@id' => url()->current() . '#webpage',
                    'url' => url()->current(),
                    'name' => ($locale === 'id' ? 'Perpustakaan Berita' : 'News Library') . ' — Lima Biji Agritech',
                    'description' => __('landing.news_library_subheading'),
                    'inLanguage' => $locale,
                ],
                [
                    '@type' => 'BreadcrumbList',
                    '@id' => url()->current() . '#breadcrumb',
                    'itemListElement' => [
                        [
                            '@type' => 'ListItem',
                            'position' => 1,
                            'name' => $locale === 'id' ? 'Beranda' : 'Home',
                            'item' => url('/'),
                        ],
                        [
                            '@type' => 'ListItem',
                            'position' => 2,
                            'name' => $locale === 'id' ? 'Berita' : 'News',
                            'item' => url()->current(),
                        ],
                    ],
                ],
            ],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endpush

@section('content')
{{-- 1. Hero Section --}}
<div class="container mx-auto px-5 pt-10 pb-10 lg:pt-5 lg:pb-5">
    <div class="max-w-5xl">
        <p data-animate="fade-up" class="text-sm lg:text-2xl font-bold tracking-[0.3em] uppercase text-light-grey mb-6">
            {{ $locale === 'id' ? 'Berita & Cerita' : 'News & Stories' }}
        </p>
        <h1 data-animate="fade-up" data-delay="0.1" class="font-display text-8xl sm:text-9xl lg:text-[12rem] text-white leading-[0.85] uppercase">
            {!! __('landing.news_library_heading') !!}
        </h1>
        <p data-animate="fade-up" data-delay="0.2" class="text-light-grey text-lg mt-8 max-w-xl">
            {{ __('landing.news_library_subheading') }}
        </p>
    </div>
</div>

{{-- 2. News Library & Filter Section --}}
<div data-animate="fade-up" class="container mx-auto px-5 py-24">

    {{-- Search & Filter Bar --}}
    <div data-animate="fade-up" class="flex flex-col md:flex-row justify-between items-center gap-6 mb-12">
        {{-- Filter Tabs --}}
        <div class="flex flex-wrap gap-3">
            @php
                $categoryOptions = collect([['slug' => 'All', 'name' => 'All']]);
                if (isset($categories) && $categories->isNotEmpty()) {
                    foreach ($categories as $dbCat) {
                        $categoryOptions->push([
                            'slug' => $dbCat->slug,
                            'name' => $dbCat->getNameForLocale(),
                        ]);
                    }
                }
            @endphp
            @foreach ($categoryOptions as $catOption)
                @php
                    $catSlug = $catOption['slug'];
                    $catName = $catOption['name'];
                    $params = [];
                    if ($catSlug !== 'All') $params['category'] = $catSlug;
                    if ($searchQuery) $params['q'] = $searchQuery;
                    $catUrl = request()->url() . ($params ? '?' . http_build_query($params) : '');
                    $isActive = $selectedCategory === $catSlug || $selectedCategory === $catName;
                @endphp
                <a href="{{ $catUrl }}"
                    class="px-6 py-2 rounded-lg text-sm font-medium transition-all duration-300
                    {{ $isActive ? 'bg-primary text-white shadow-md' : 'bg-surface-alt border border-border text-light-grey hover:border-primary/40 hover:text-white' }}">
                    {{ $catName }}
                </a>
            @endforeach
        </div>

        {{-- Search Form --}}
        <form method="GET" action="{{ request()->url() }}" class="flex items-center gap-2 w-full md:w-auto">
            @if($selectedCategory !== 'All')
                <input type="hidden" name="category" value="{{ $selectedCategory }}">
            @endif
            <div class="relative w-full md:w-64">
                <input type="text" name="q" value="{{ $searchQuery }}" placeholder="{{ __('landing.news_search_placeholder') }}"
                    class="w-full px-4 py-2 pl-10 text-sm bg-surface-alt border border-border rounded-lg text-white placeholder:text-light-grey/50 focus:outline-none focus:border-primary transition-colors">
                <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-light-grey" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <button type="submit" class="px-4 py-2 bg-primary text-white font-medium rounded-lg text-sm hover:bg-primary-hover transition-colors">
                {{ __('landing.news_search_btn') }}
            </button>
        </form>
    </div>

    @if($articles->isNotEmpty())
        <div data-animate="stagger" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($articles as $news)
                <div class="stagger-item">
                    <x-news-card :news="$news" />
                </div>
            @endforeach
        </div>

        @if($articles->hasPages())
            <div class="mt-12 flex justify-center">
                {{ $articles->links() }}
            </div>
        @endif
    @else
        <div class="text-center py-20">
            <p class="text-light-grey text-lg">{{ __('landing.news_no_articles') }}</p>
        </div>
    @endif
</div>
@endsection
