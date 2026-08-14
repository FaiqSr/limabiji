@props([
    'data'     => [],
    'locale'   => 'en',
    'articles' => null,   // Eloquent collection or paginator from controller
    'page'     => null,
])

@php
    $hidden  = $data['field_hidden'] ?? [];
    $heading = $data['heading'] ?? ($locale === 'id' ? 'Berita' : "WHAT'S NEW?");
    $showAll = !empty($data['show_all']);
    $limit   = isset($data['limit']) && is_numeric($data['limit']) ? intval($data['limit']) : 6;

    // Primary: use DB collection / paginator from controller
    // Fallback: use items from block content JSON
    $items = $articles;
    if (empty($items) || (is_countable($items) && count($items) === 0)) {
        $items = $data['items'] ?? [];
        if (is_string($items)) {
            $items = json_decode($items, true) ?: [];
        }
    }

    $isPaginator = $items instanceof \Illuminate\Contracts\Pagination\Paginator;

    // If not a paginator (e.g. on homepage or preview), slice to limit UNLESS show_all is enabled or limit === 0
    if (!$isPaginator && !empty($items) && !$showAll && $limit > 0) {
        $items = collect($items)->take($limit);
    }

    $hasArticles = !empty($items) && ($isPaginator ? $items->total() > 0 : (is_countable($items) ? count($items) > 0 : false));

    $subtitleEn = 'Latest stories from our export desk and fermentation lab.';
    $subtitleId = 'Berita terbaru dari meja ekspor dan lab fermentasi kami.';
    $subtitle   = $data['subtitle'] ?? ($locale === 'id' ? $subtitleId : $subtitleEn);

    $isFullPage = ($page?->slug ?? '') === 'news' || request()->is('news') || request()->is('*/news');
    $hasPagination = $items instanceof \Illuminate\Contracts\Pagination\Paginator;

    $dbCategories = \App\Models\Category::orderBy('name')->get();
    $categoryOptions = collect([['slug' => 'All', 'name' => 'All']]);
    if ($dbCategories->isNotEmpty()) {
        foreach ($dbCategories as $dbCat) {
            $categoryOptions->push([
                'slug' => $dbCat->slug,
                'name' => $dbCat->getNameForLocale(),
            ]);
        }
    } else {
        foreach (['Export Market', 'Innovation', 'Sustainability'] as $staticCat) {
            $categoryOptions->push(['slug' => $staticCat, 'name' => $staticCat]);
        }
    }

    $selectedCategory = request()->get('category', 'All');
    $searchQuery = request()->get('q', '');
    $showFilter = $isFullPage || request()->filled('q') || request()->filled('category');
@endphp

<div data-animate="fade-up" class="container mx-auto px-5 py-24">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-8 gap-4">
        @if(empty($hidden['heading']) && $heading)
            <x-section-heading :title="$heading" :subtitle="empty($hidden['subtitle']) ? $subtitle : null" />
        @endif
        @if(!$isFullPage)
        <a href="{{ url('/news') }}"
            class="group flex items-center gap-2 text-dark font-bold hover:text-primary transition-colors duration-300">
            <span>View All News</span>
            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
            </svg>
        </a>
        @endif
    </div>

    @if($showFilter)
    {{-- Search & Filter Bar --}}
    <div data-animate="fade-up" class="flex flex-col md:flex-row justify-between items-center gap-6 mb-12">
        {{-- Filter Tabs --}}
        <div class="flex flex-wrap gap-3">
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
                    {{ $isActive ? 'bg-primary text-dark shadow-md' : 'bg-surface-alt border border-border text-light-grey hover:border-primary/30 hover:text-dark' }}">
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
                <input type="text" name="q" value="{{ $searchQuery }}" placeholder="{{ $locale === 'id' ? 'Cari berita...' : 'Search articles...' }}"
                    class="w-full px-4 py-2 pl-10 text-sm bg-surface-alt border border-border rounded-lg text-dark focus:outline-none focus:border-primary transition-colors">
                <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-light-grey" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <button type="submit" class="px-4 py-2 bg-primary text-dark font-medium rounded-lg text-sm hover:bg-primary-hover transition-colors">
                {{ $locale === 'id' ? 'Cari' : 'Search' }}
            </button>
        </form>
    </div>
    @endif

    @if($hasArticles)
        <div data-animate="stagger" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($items as $news)
                <div class="stagger-item">
                    <x-news-card :news="$news" />
                </div>
            @endforeach
        </div>

        @if($hasPagination && $items->hasPages())
            <div class="mt-12 flex justify-center">
                {{ $items->links() }}
            </div>
        @endif
    @else
        <div class="text-center py-20">
            <p class="text-light-grey text-lg">{{ $locale === 'id' ? 'Tidak ada berita yang ditemukan.' : 'No articles found.' }}</p>
        </div>
    @endif
</div>
