@props([
    'data'   => [],
    'locale' => 'en',
])

@php
    $heading    = $data['heading']     ?? '';
    $body       = $data['body']        ?? '';
    $btnText    = $data['button_text'] ?? '';
    $btnUrl     = $data['button_url']  ?? '';
@endphp

@if($heading || $body)
<div class="container mx-auto px-5 py-24">
    <div data-animate="scale-in" class="card-solid p-10 sm:p-16 relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 opacity-5 pointer-events-none select-none">
            <span class="font-display text-[12rem] text-dark leading-none">5</span>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center relative z-10">
            <div class="lg:col-span-7">
                <h2 class="font-display text-3xl sm:text-4xl lg:text-5xl text-dark leading-tight uppercase">
                    {{ $heading }}
                </h2>
            </div>
            <div class="lg:col-span-5">
                <p class="text-light-grey text-base leading-relaxed mb-8">
                    {{ $body }}
                </p>
                @if($btnText)
                    <x-btn-primary :href="$btnUrl" :label="$btnText" />
                @endif
            </div>
        </div>
    </div>
</div>
@endif
