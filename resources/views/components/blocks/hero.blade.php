@props([
    'data'   => [],
    'locale' => 'en',
])

@php
    $hidden     = $data['field_hidden'] ?? [];
    $label      = $data['label']      ?? '';
    $heading    = $data['heading']    ?? '';
    $subheading = $data['subheading'] ?? '';
    $ctaText    = $data['cta_text']   ?? '';
    $ctaUrl     = $data['cta_url']    ?? '';
    $cta2Text   = $data['cta2_text']  ?? '';
    $cta2Url    = $data['cta2_url']   ?? '';

    $showBtn1 = empty($hidden['cta_text']);
    $showBtn2 = empty($hidden['cta2_text']);
@endphp

<div class="container mx-auto px-5 pt-10 pb-10 lg:pt-5 lg:pb-5">
    <div class="max-w-5xl">
        @if(empty($hidden['label']) && $label)
            <p data-animate="fade-up" class="text-sm lg:text-2xl font-bold tracking-[0.3em] uppercase text-light-grey mb-6">{{ $label }}</p>
        @endif
        @if(empty($hidden['heading']) && $heading)
            <h1 data-animate="fade-up" data-delay="0.1" class="font-display text-8xl sm:text-9xl lg:text-[12rem] text-dark leading-[0.85] uppercase">
                {!! $heading !!}
            </h1>
        @endif
        @if(empty($hidden['subheading']) && $subheading)
            <p data-animate="fade-up" data-delay="0.2" class="text-light-grey text-lg mt-8 max-w-xl">
                {{ $subheading }}
            </p>
        @endif
        @if($showBtn1 || $showBtn2)
            <div data-animate="fade-up" data-delay="0.3" class="flex sm:flex-row flex-col items-start gap-4 mt-10">
                @if($showBtn1)
                    <x-btn-primary :href="empty($hidden['cta_url']) && $ctaUrl ? url($ctaUrl) : url('/innovation')" :label="$ctaText ?: __('nav.innovation')" />
                @endif
                @if($showBtn2)
                    <x-btn-outline :href="empty($hidden['cta2_url']) && $cta2Url ? url($cta2Url) : url('/news')" :label="$cta2Text ?: __('nav.news')" />
                @endif
            </div>
        @endif
    </div>
</div>
