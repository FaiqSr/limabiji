@extends('layouts.landingpages')

@push('title', ($page->title ?? 'Preview') . ' — Live Preview')

@push('modules')
    {{-- Swiper Bundle for Origins/Testimonials --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@14.0.1/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@14.0.1/swiper-bundle.min.js"></script>
@endpush

@section('content')
@php
    $locale = $locale ?? app()->getLocale();

    // Resolve block type => component mapping
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
        'contact'         => 'blocks.contact',
    ];
@endphp

@if($page && $page->blocks && $page->blocks->isNotEmpty())
    @foreach($page->blocks->where('is_visible', true)->sortBy('order') as $block)
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
                :page="$page"
                :articles="$articles ?? collect()"
                :testimonials="$testimonials ?? collect()"
                :origins="$origins ?? collect()"
                :export-destinations="$exportDestinations ?? collect()"
                :settings="$settings ?? collect()"
            />
        @endif
    @endforeach
@else
    <div class="container mx-auto px-5 py-24 text-center">
        <h1 class="font-display text-4xl text-dark mb-4">No Content Blocks</h1>
        <p class="text-light-grey">Add blocks in the admin editor to see them previewed here live in real-time.</p>
    </div>
@endif
@endsection
