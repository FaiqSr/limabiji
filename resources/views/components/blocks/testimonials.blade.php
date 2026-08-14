@props([
    'data'         => [],
    'locale'       => 'en',
    'testimonials' => null,  // Eloquent collection or paginator from controller
    'page'         => null,
])

@php
    $hidden  = $data['field_hidden'] ?? [];
    $heading = $data['heading'] ?? ($locale === 'id' ? 'Testimoni' : 'Testimonials');
    $showAll = !empty($data['show_all']);
    $limit   = isset($data['limit']) && is_numeric($data['limit']) ? intval($data['limit']) : 6;

    // Primary: use DB collection / paginator from controller
    // Fallback: use items from block content JSON
    $items = $testimonials;
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

    $hasTestimonials = !empty($items) && ($isPaginator ? $items->total() > 0 : (is_countable($items) ? count($items) > 0 : false));

    $subtitleEn = 'Trusted by roasters and importers worldwide.';
    $subtitleId = 'Dipercaya oleh roaster dan importir di seluruh dunia.';
    $subtitle   = $data['subtitle'] ?? ($locale === 'id' ? $subtitleId : $subtitleEn);

    $isFullPage = ($page?->slug ?? '') === 'testimonials' || request()->is('testimonials') || request()->is('*/testimonials');
    $hasPagination = $items instanceof \Illuminate\Contracts\Pagination\Paginator;
@endphp

@if($hasTestimonials)
<div data-animate="fade-up" class="container mx-auto px-5 py-24">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-12 gap-4">
        @if(empty($hidden['heading']) && $heading)
            <x-section-heading :title="$heading" :subtitle="empty($hidden['subtitle']) ? $subtitle : null" />
        @endif
        @if(!$isFullPage)
        <a href="{{ url('/testimonials') }}"
            class="group flex items-center gap-2 text-dark font-bold hover:text-primary transition-colors duration-300">
            <span>View All Testimonials</span>
            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
            </svg>
        </a>
        @endif
    </div>

    <div data-animate="stagger" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($items as $testimonial)
            <div class="stagger-item">
                <x-testimonial-card :testimonial="$testimonial" />
            </div>
        @endforeach
    </div>

    @if($hasPagination && $items->hasPages())
        <div class="mt-12 flex justify-center">
            {{ $items->links() }}
        </div>
    @endif
</div>
@endif
