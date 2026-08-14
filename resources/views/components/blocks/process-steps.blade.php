@props([
    'data'   => [],
    'locale' => 'en',
])

@php
    $hidden   = $data['field_hidden'] ?? [];
    $heading  = $data['heading']  ?? '';
    $subtitle = $data['subtitle'] ?? '';
    $steps    = $data['items']    ?? [];
    if (is_string($steps)) {
        $steps = json_decode($steps, true) ?: [];
    }
    $hasSteps = !empty($steps) && is_array($steps) && count($steps) > 0;
@endphp

@if($hasSteps)
<div class="container mx-auto px-5 py-24">
    @if(empty($hidden['heading']) && $heading)
        <x-section-heading :title="$heading" :subtitle="empty($hidden['subtitle']) ? ($subtitle ?: null) : null" />
    @endif

    <div data-animate="fade-up" class="space-y-24">
        @foreach ($steps as $index => $step)
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 items-center">
                {{-- Image --}}
                <div class="{{ $index % 2 === 1 ? 'lg:order-2' : '' }} relative">
                    <div class="rounded-lg overflow-hidden h-80 lg:h-96 bg-surface-alt border border-border">
                        @if(!empty($step['image']))
                            <img src="{{ $step['image'] }}" alt="{{ $step['title'] ?? '' }}"
                                class="w-full h-full object-cover hover:scale-105 transition-transform duration-700">
                        @endif
                    </div>
                    <div class="absolute -bottom-4 -left-4 bg-primary text-dark w-16 h-16 rounded-lg flex items-center justify-center font-display text-2xl shadow-lg">
                        {{ $step['step'] ?? ($index + 1) }}
                    </div>
                </div>

                {{-- Content --}}
                <div class="{{ $index % 2 === 1 ? 'lg:order-1' : '' }}">
                    <h3 class="text-dark font-display text-3xl lg:text-4xl mb-4">{{ $step['title'] ?? '' }}</h3>
                    <p class="text-light-grey text-base leading-relaxed mb-6">{!! $step['description'] ?? '' !!}</p>
                    @if(!empty($step['details']))
                        <div class="flex flex-wrap gap-3">
                            @foreach (array_map('trim', explode(',', $step['details'])) as $detail)
                                @if ($detail)
                                    <span class="text-xs font-bold text-primary bg-primary/10 border border-primary/30 px-4 py-1.5 rounded-lg">
                                        {{ $detail }}
                                    </span>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>

@once
    @push('scripts')
        <script type="application/ld+json">
            {!! json_encode([
                '@context' => 'https://schema.org',
                '@type' => 'HowTo',
                'name' => $heading ?: 'Process Steps',
                'description' => $subtitle ?: 'How our process works',
                'step' => array_map(function ($step, $idx) {
                    return [
                        '@type' => 'HowToStep',
                        'position' => intval($step['step'] ?? ($idx + 1)),
                        'name' => $step['title'] ?? '',
                        'text' => strip_tags($step['description'] ?? ''),
                    ];
                }, $steps, array_keys($steps)),
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
        </script>
    @endpush
@endonce
@endif
