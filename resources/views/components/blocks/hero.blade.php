@props([
    'data'   => [],
    'locale' => 'en',
])

@php
    $label      = $data['label']      ?? '';
    $heading    = $data['heading']    ?? '';
    $subheading = $data['subheading'] ?? '';
@endphp

<div class="container mx-auto px-5 pt-10 pb-10 lg:pt-5 lg:pb-5">
    <div class="max-w-5xl">
        @if($label)
            <p data-animate="fade-up" class="text-sm lg:text-2xl font-bold tracking-[0.3em] uppercase text-light-grey mb-6">{{ $label }}</p>
        @endif
        @if($heading)
            <h1 data-animate="fade-up" data-delay="0.1" class="font-display text-8xl sm:text-9xl lg:text-[12rem] text-dark leading-[0.85] uppercase">
                {!! $heading !!}
            </h1>
        @endif
        @if($subheading)
            <p data-animate="fade-up" data-delay="0.2" class="text-light-grey text-lg mt-8 max-w-xl">
                {{ $subheading }}
            </p>
        @endif
        <div data-animate="fade-up" data-delay="0.3" class="flex sm:flex-row flex-col items-start gap-4 mt-10">
            <x-btn-primary href="{{ route('landingpages.innovation') }}" label="{{ __('nav.innovation') }}" />
            <x-btn-outline href="{{ route('landingpages.news') }}" label="{{ __('nav.news') }}" />
        </div>
    </div>
</div>
