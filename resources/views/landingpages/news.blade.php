@extends('layouts.landingpages')

@php($locale = app()->getLocale())
@php($heroBlock = $page?->blocks->firstWhere('block_type', 'hero'))
@php($hero = $heroBlock?->getContent($locale) ?: [])
@php($heroLabel = $hero['label'] ?? '')
@php($heroHeading = $hero['heading'] ?? '')
@php($heroSubheading = $hero['subheading'] ?? '')

@push('title', 'Coffee Industry News — Lima Biji Agritech')

@push('meta')
    <meta name="description" content="{{ strip_tags($heroSubheading) }}">
    <meta property="og:title" content="Coffee Industry News — Lima Biji Agritech">
    <meta property="og:description" content="{{ strip_tags($heroSubheading) }}">
    <meta property="og:type" content="website">
    <link rel="canonical" href="{{ url()->current() }}">
@endpush

@section('content')
    <div class="container mx-auto px-5 pt-10 pb-10 lg:pt-5 lg:pb-5">
        <div class="max-w-3xl">
            @if($heroLabel)
                <p data-animate="fade-up" class="text-sm font-bold tracking-[0.3em] uppercase text-light-grey mb-6">{{ $heroLabel }}</p>
            @endif
            @if($heroHeading)
                <h1 data-animate="fade-up" data-delay="0.1" class="font-display text-7xl sm:text-9xl lg:text-[10rem] text-dark leading-[0.85] uppercase">
                    {!! $heroHeading !!}
                </h1>
            @endif
            @if($heroSubheading)
                <p data-animate="fade-up" data-delay="0.2" class="text-light-grey text-lg mt-6 max-w-xl">
                    {!! $heroSubheading !!}
                </p>
            @endif
        </div>
    </div>

    <div class="container mx-auto px-5 pb-24">
        @php($categories = ['All', 'Export Market', 'Innovation', 'Sustainability'])
        @php($selectedCategory = request()->get('category', 'All'))

        {{-- Filter Tabs --}}
        <div data-animate="fade-up" class="flex flex-wrap justify-center gap-3 mb-12">
            @foreach ($categories as $cat)
                <a href="{{ route('landingpages.news', $cat === 'All' ? [] : ['category' => $cat]) }}"
                    class="px-6 py-2 rounded-lg text-sm font-medium transition-all duration-300
                    {{ $selectedCategory === $cat ? 'bg-primary text-dark shadow-md' : 'bg-surface-alt border border-border text-light-grey hover:border-primary/30 hover:text-dark' }}">
                    {{ $cat }}
                </a>
            @endforeach
        </div>

        {{-- News Grid --}}
        <div data-animate="stagger" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($articles as $news)
                <div class="stagger-item">
                    <x-news-card :news="$news" />
                </div>
            @endforeach
        </div>

        @if (count($articles) === 0)
            <div class="text-center py-20">
                <p class="text-light-grey text-lg">No articles found in this category.</p>
            </div>
        @endif
    </div>
@endsection
