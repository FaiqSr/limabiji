@extends('layouts.landingpages')

@push('title', $article->title . ' — Lima Biji Agritech')

@push('meta')
    <meta name="description" content="{{ Str::limit(strip_tags($article->getExcerptForLocale(app()->getLocale())), 160) }}">
    <meta property="og:title" content="{{ $article->title }} — Lima Biji Agritech">
    <meta property="og:description" content="{{ Str::limit(strip_tags($article->getExcerptForLocale(app()->getLocale())), 160) }}">
    <meta property="og:type" content="article">
    @if($article->image)
        <meta property="og:image" content="{{ $article->image }}">
    @endif
    <link rel="canonical" href="{{ url()->current() }}">
@endpush

@section('content')

        {{-- Featured Image --}}
        @if($article->image)
            <div data-animate="fade-in" class="container mx-auto mb-12">
                <img src="{{ $article->image }}" alt="{{ $article->title }}"
                    class="w-full h-80 sm:h-96 lg:h-[28rem] object-cover rounded-lg shadow-md border border-border">
            </div>
        @endif

    <article class="container mx-auto px-5 pb-12 lg:pb-16 pt-6 lg:pt-8 max-w-4xl">
        {{-- Header --}}
        <header data-animate="fade-up" class="mb-10">
            <div class="flex items-center gap-3 mb-6">
                <a href="{{ url('/news') }}" class="inline-flex items-center gap-2 text-sm text-light-grey hover:text-primary transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    News
                </a>
                <span class="text-light-grey/40">/</span>
                <span class="text-sm text-light-grey">
                    @if($article->categories->isNotEmpty())
                        {{ $article->categories->map(fn($c) => $c->getNameForLocale())->join(', ') }}
                    @else
                        {{ $article->category }}
                    @endif
                </span>
            </div>

            <h1 class="font-display text-6xl sm:text-7xl lg:text-8xl text-white leading-[0.85] uppercase">
                {{ $article->getTitleForLocale(app()->getLocale()) }}
            </h1>

            <div class="flex flex-wrap items-center gap-3 text-sm text-light-grey/70 mt-6">
                <time datetime="{{ $article->published_at?->toIso8601String() }}">
                    {{ $article->published_at ? $article->published_at->format('d M Y') : $article->created_at->format('d M Y') }}
                </time>
                @if($article->author)
                    <span class="text-light-grey/30">·</span>
                    <span>By {{ $article->author->name }}</span>
                @endif
            </div>
        </header>


        {{-- Body Content (sanitized) --}}
        <div data-animate="fade-up" data-delay="0.1" class="article-body">
            {!! $article->getHtmlContentForLocale(app()->getLocale()) !!}
        </div>
    </article>

    {{-- Related Articles --}}
    @if(count($related) > 0)
        <section class="container mx-auto px-5 pb-24">
            <div class="flex items-end justify-between mb-10">
                <div>
                    <p class="section-heading">Related Stories</p>
                    <h2 class="section-title">More From The Newsroom</h2>
                </div>
                <a href="{{ url('/news') }}" class="hidden sm:inline-flex items-center gap-2 text-sm font-medium text-primary hover:text-primary-hover transition-colors">
                    View All News
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>

            <div data-animate="stagger" class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach ($related as $item)
                    <div class="stagger-item">
                        <x-news-card :news="$item" />
                    </div>
                @endforeach
            </div>
        </section>
    @endif
@endsection
