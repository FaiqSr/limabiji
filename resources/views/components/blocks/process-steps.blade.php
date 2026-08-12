@props([
    'data'   => [],
    'locale' => 'en',
])

@php
    $heading  = $data['heading']  ?? '';
    $subtitle = $data['subtitle'] ?? '';
    $steps    = $data['items']    ?? [];
    if (is_string($steps)) {
        $steps = json_decode($steps, true) ?: [];
    }
@endphp

@if(!empty($steps) && is_array($steps) && count($steps) > 0)
<div class="container mx-auto px-5 py-24">
    @if($heading)
        <x-section-heading :title="$heading" :subtitle="$subtitle ?: null" />
    @endif
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach ($steps as $step)
        <div class="group relative rounded-2xl border border-border bg-surface-alt overflow-hidden transition-all duration-500 hover:border-primary/40 hover:shadow-lg">
            @if(!empty($step['image']))
                <div class="h-48 overflow-hidden">
                    <img src="{{ $step['image'] }}" alt="{{ $step['title'] ?? '' }}"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                </div>
            @endif
            <div class="p-6">
                @if(!empty($step['step']))
                    <span class="inline-block font-mono text-xs font-bold text-primary bg-primary/10 border border-primary/20 px-3 py-1 rounded-full mb-3">
                        {{ $step['step'] }}
                    </span>
                @endif
                @if(!empty($step['title']))
                    <h3 class="font-display text-2xl text-dark mb-3">{{ $step['title'] }}</h3>
                @endif
                @if(!empty($step['description']))
                    <p class="text-dark/60 text-sm leading-relaxed">{{ $step['description'] }}</p>
                @endif
                @if(!empty($step['details']))
                    <ul class="mt-4 space-y-1">
                        @foreach(explode(',', $step['details']) as $detail)
                        <li class="flex items-center gap-2 text-xs text-dark/50">
                            <span class="w-1.5 h-1.5 rounded-full bg-primary flex-shrink-0"></span>
                            {{ trim($detail) }}
                        </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif
