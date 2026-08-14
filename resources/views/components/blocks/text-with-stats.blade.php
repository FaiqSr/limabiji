@props([
    'data'   => [],
    'locale' => 'en',
])

@php
    $hidden     = $data['field_hidden'] ?? [];
    $heading    = $data['heading']   ?? '';
    $body       = $data['body']      ?? '';
    $body2      = $data['body2']     ?? '';
    $checklist  = $data['checklist'] ?? '';
    $statsItems = $data['items']    ?? [];
    if (is_string($statsItems)) {
        $statsItems = json_decode($statsItems, true) ?: [];
    }
    $checklistItems = array_filter(array_map('trim', explode(',', $checklist)));
@endphp

@if((empty($hidden['heading']) && $heading) || (empty($hidden['body']) && $body) || !empty($statsItems))
<div class="container mx-auto px-5 py-24">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">
        {{-- Left: Text + Checklist --}}
        <div>
            @if(empty($hidden['heading']) && $heading)
                <h2 class="font-display text-4xl sm:text-5xl text-dark uppercase leading-tight mb-6">{{ $heading }}</h2>
            @endif
            @if(empty($hidden['body']) && $body)
                <p class="text-dark/70 text-base leading-relaxed mb-4">{{ $body }}</p>
            @endif
            @if(empty($hidden['body2']) && $body2)
                <p class="text-dark/70 text-base leading-relaxed mb-6">{{ $body2 }}</p>
            @endif
            @if(empty($hidden['checklist']) && count($checklistItems) > 0)
                <ul class="space-y-2 mt-6">
                    @foreach($checklistItems as $item)
                    <li class="flex items-center gap-3 text-dark/80 text-sm font-medium">
                        <span class="w-5 h-5 rounded-full bg-primary/10 border border-primary/30 flex items-center justify-center flex-shrink-0">
                            <svg class="w-3 h-3 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                        </span>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
            @endif
        </div>

        {{-- Right: Stats Grid --}}
        @if(!empty($statsItems))
        <div class="grid grid-cols-2 gap-6">
            @foreach($statsItems as $stat)
            <div class="card-solid p-6 text-center">
                <p class="font-display text-5xl text-dark">{{ $stat['value'] ?? '' }}</p>
                <p class="text-dark/40 text-sm mt-2">{{ $stat['label'] ?? '' }}</p>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endif
