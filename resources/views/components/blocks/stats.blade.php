@props([
    'data'   => [],
    'locale' => 'en',
])

@php
    $heading    = $data['heading'] ?? '';
    $statsItems = $data['items']   ?? [];
    if (is_string($statsItems)) {
        $statsItems = json_decode($statsItems, true) ?: [];
    }
@endphp

@if($heading || (is_array($statsItems) && count($statsItems) > 0))
<div class="container mx-auto px-5 py-24">
    <div data-animate="scale-in" class="card-solid p-10 sm:p-16">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <p class="text-dark text-2xl lg:text-3xl font-medium leading-tight">
                    {{ $heading }}
                </p>
                <div class="mt-8">
                    <x-btn-primary href="{{ route('landingpages.innovation') }}" label="{{ __('nav.innovation') }}" />
                </div>
            </div>
            <div class="grid grid-cols-2 gap-8">
                @foreach ($statsItems as $stat)
                <div>
                    <p class="text-dark font-display text-5xl">{{ $stat['value'] ?? '' }}</p>
                    <p class="text-dark/40 text-sm mt-1">{{ $stat['label'] ?? '' }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif
