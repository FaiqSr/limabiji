@props([
    'data'    => [],
    'locale'  => 'en',
    'origins' => null,   // Eloquent collection from controller (primary)
])

@php
    $hidden = $data['field_hidden'] ?? [];
    // Primary: use DB collection from controller
    // Fallback: use items from block content JSON
    $items = $origins;
    if (empty($items) || (is_countable($items) && count($items) === 0)) {
        $items = $data['items'] ?? [];
        if (is_string($items)) {
            $items = json_decode($items, true) ?: [];
        }
    }

    $showAll = !empty($data['show_all']);
    $limit   = isset($data['limit']) && is_numeric($data['limit']) ? intval($data['limit']) : 6;

    if (!empty($items) && !$showAll && $limit > 0) {
        $items = collect($items)->take($limit);
    }

    $heading  = !empty($data['heading']) ? $data['heading'] : __('landing.origins_title');
    $subtitle = !empty($data['subtitle']) ? $data['subtitle'] : __('landing.about_subtitle');
    $hasOrigins = !empty($items) && is_countable($items) && count($items) > 0;
@endphp

@if($hasOrigins)
<div data-animate="fade-up" class="container mx-auto px-5 pt-5 pb-15">
    @if(empty($hidden['heading']) && $heading)
        <x-section-heading :title="$heading" :subtitle="empty($hidden['subtitle']) ? $subtitle : null" />
    @endif
</div>

<div class="mb-20">
    <div class="swiper swiper-card">
        <div class="swiper-wrapper">
            @foreach ($items as $origin)
                @php($originName  = is_object($origin) ? $origin->name  : ($origin['name']  ?? ''))
                @php($originSlug  = is_object($origin) ? ($origin->slug ?: Str::slug($origin->name)) : ($origin['slug'] ?? Str::slug($origin['name'] ?? '')))
                @php($originImage = is_object($origin) ? $origin->image : ($origin['image'] ?? ''))
                <div class="swiper-slide !w-[80vw] sm:!w-[30rem] lg:!w-[40rem]">
                    <a href="{{ route('landingpages.origins', $originSlug) }}"
                        class="group block h-[25rem] sm:h-[30rem] rounded-lg overflow-hidden relative mb-5 bg-surface-alt border border-border">
                        @if($originImage)
                            <img src="{{ $originImage }}"
                                alt="{{ $originName }}"
                                class="w-full h-full object-cover group-hover:scale-125 transition-all duration-500">
                        @endif
                        <p class="absolute bottom-5 left-8 text-4xl font-display text-white py-2 transition-all duration-500 ease-in-out
                            group-hover:bottom-1/2 group-hover:left-1/2 group-hover:-translate-x-1/2 group-hover:scale-150
                            group-hover:translate-y-1/2 pointer-events-none">
                            {{ $originName }}
                        </p>
                    </a>
                </div>
            @endforeach
        </div>
        <div class="container mx-auto mb-5">
            <div class="swiper-scrollbar !relative !bottom-0 mt-6 !h-4  hover:cursor-pointer border-dark rounded-lg"></div>
        </div>
    </div>
</div>

@once
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        function getContainerOffset() {
            const container = document.querySelector('.container');
            if (!container) return 20;
            const windowWidth = window.innerWidth;
            const containerWidth = container.getBoundingClientRect().width;
            const padding = 20;
            const offset = ((windowWidth - containerWidth) / 2) + padding;
            return Math.max(offset, padding);
        }

        const dynamicOffset = getContainerOffset();

        const swiper = new Swiper('.swiper-card', {
            slidesPerView: 'auto',
            spaceBetween: 20,
            loop: false,
            slidesOffsetBefore: dynamicOffset,
            slidesOffsetAfter: dynamicOffset,
            grabCursor: true,
            scrollbar: {
                el: '.swiper-scrollbar',
                draggable: true,
                snapOnRelease: true,
            },
        });

        window.addEventListener('resize', function () {
            const newOffset = getContainerOffset();
            swiper.params.slidesOffsetBefore = newOffset;
            swiper.params.slidesOffsetAfter = newOffset;
            swiper.update();
        });
    });
</script>
@endpush
@endonce
@endif
