@extends('layouts.landingpages')

@push('title', 'Lima Biji Agritech — Specialty Enzymatic Civet Coffee')

@push('modules')
    {{-- Swiper (always needed for origins) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@14.0.1/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@14.0.1/swiper-bundle.min.js"></script>
@endpush

@section('content')
@php
    $locale = app()->getLocale();

    // Resolve block type => component mapping (underscore => hyphen for Blade)
    $blockComponentMap = [
        'hero'            => 'blocks.hero',
        'text'            => 'blocks.text',
        'stats'           => 'blocks.stats',
        'cta'             => 'blocks.cta',
        'faq'             => 'blocks.faq',
        'articles'        => 'blocks.articles',
        'testimonials'    => 'blocks.testimonials',
        'origins'         => 'blocks.origins',
        'export_map'      => 'blocks.export-map',
        'process_steps'   => 'blocks.process-steps',
        'text_with_stats' => 'blocks.text-with-stats',
    ];
@endphp

@if($homePage && $homePage->blocks->isNotEmpty())
    @foreach($homePage->blocks->where('is_visible', true)->sortBy('order') as $block)
        @php
            $blockType  = $block->block_type;
            $component  = $blockComponentMap[$blockType] ?? null;
            $data       = $block->getContent($locale);
        @endphp

        @if($component && \Illuminate\Support\Facades\View::exists("components.{$component}"))
            <x-dynamic-component
                :component="$component"
                :data="$data"
                :locale="$locale"
                :articles="$articles ?? collect()"
                :testimonials="$testimonials ?? collect()"
                :origins="$origins ?? collect()"
                :export-destinations="$exportDestinations ?? collect()"
            />
        @endif
    @endforeach
@else
    {{-- Fallback: render export map if no blocks configured yet --}}
    <x-blocks.export-map
        :data="[]"
        :locale="$locale"
        :export-destinations="$exportDestinations ?? collect()"
    />
@endif
@endsection
