@extends('layouts.landingpages')

@php($locale = app()->getLocale())

@php($heroBlock = $page?->blocks->firstWhere('block_type', 'hero'))
@php($hero = $heroBlock?->getContent($locale) ?: [])
@php($heroLabel = $hero['label'] ?? '')
@php($heroHeading = $hero['heading'] ?? '')
@php($heroSubheading = $hero['subheading'] ?? '')

@php($stepsBlock = $page?->blocks->firstWhere('block_type', 'process_steps'))
@php($stepsContent = $stepsBlock?->getContent($locale) ?: [])
@php($stepsHeading = $stepsContent['heading'] ?? '')
@php($stepsSubtitle = $stepsContent['subtitle'] ?? '')
@php($stepsItems = $stepsContent['items'] ?? [])

@php($whyBlock = $page?->blocks->firstWhere('block_type', 'text_with_stats'))
@php($whyContent = $whyBlock?->getContent($locale) ?: [])
@php($whyHeading = $whyContent['heading'] ?? '')
@php($whyBody = $whyContent['body'] ?? '')
@php($whyBody2 = $whyContent['body2'] ?? '')
@php($whyChecklist = array_filter(array_map('trim', explode(',', $whyContent['checklist'] ?? ''))))
@php($whyStats = $whyContent['items'] ?? [])

@php($ctaBlock = $page?->blocks->firstWhere('block_type', 'cta'))
@php($ctaContent = $ctaBlock?->getContent($locale) ?: [])
@php($ctaHeading = $ctaContent['heading'] ?? '')
@php($ctaBody = $ctaContent['body'] ?? '')
@php($ctaButtonText = $ctaContent['button_text'] ?? '')
@php($ctaButtonUrl = $ctaContent['button_url'] ?? '')

@push('title', 'Enzymatic Civet Process — Lima Biji Agritech')

@php($metaSub = strip_tags($heroSubheading))
@php($metaSub = strlen($metaSub) > 160 ? substr($metaSub, 0, 160) : $metaSub)

@push('meta')
    <meta name="description" content="{{ $metaSub }}">
    <meta property="og:title" content="Enzymatic Civet Process — Lima Biji Agritech">
    <meta property="og:description" content="{{ $metaSub }}">
    <meta property="og:type" content="website">
    <link rel="canonical" href="{{ url()->current() }}">
@endpush

@section('content')
    {{-- Hero --}}
    <div class="container mx-auto px-5 pt-10 pb-10 lg:pt-15 lg:pb-15">
        <div class="max-w-3xl">
            <p data-animate="fade-up" class="text-sm font-bold tracking-[0.3em] uppercase text-light-grey mb-6">{{ $heroLabel }}</p>
            <h1 data-animate="fade-up" data-delay="0.1" class="font-display text-7xl sm:text-9xl lg:text-[10rem] text-dark leading-[0.85] uppercase">
                {!! $heroHeading !!}
            </h1>
            <p data-animate="fade-up" data-delay="0.2" class="text-light-grey text-lg mt-6 max-w-xl">
                {!! $heroSubheading !!}
            </p>
        </div>
    </div>

    {{-- Process Steps --}}
    @if(!empty($stepsItems) && is_array($stepsItems))
    <div class="container mx-auto px-5 py-24">
        <x-section-heading title="{{ $stepsHeading }}" subtitle="{{ $stepsSubtitle }}" />

        <div data-animate="fade-up" class="space-y-24">
            @foreach ($stepsItems as $index => $step)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 items-center">
                    {{-- Image --}}
                    <div class="{{ $index % 2 === 1 ? 'lg:order-2' : '' }} relative">
                        <div class="rounded-lg overflow-hidden h-80 lg:h-96">
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
                        <div class="flex flex-wrap gap-3">
                            @foreach (array_map('trim', explode(',', $step['details'] ?? '')) as $detail)
                                @if ($detail)
                                    <span class="text-xs font-bold text-primary bg-primary/10 border border-primary/30 px-4 py-1.5 rounded-lg">
                                        {{ $detail }}
                                    </span>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Why Cruelty-Free Matters --}}
    @if($whyHeading || $whyBody || (is_array($whyStats) && count($whyStats) > 0))
    <div class="container mx-auto px-5 py-24">
        <div data-animate="scale-in" class="card-solid p-8 sm:p-12 lg:p-16">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="font-display text-3xl lg:text-4xl text-dark leading-tight mb-6 uppercase">
                        {{ $whyHeading }}
                    </h2>
                    <p class="text-light-grey text-base leading-relaxed mb-4">
                        {!! $whyBody !!}
                    </p>
                    <p class="text-light-grey text-base leading-relaxed mb-4">
                        {!! $whyBody2 !!}
                    </p>
                    @if ($whyChecklist)
                    <ul class="space-y-3 text-light-grey">
                        @foreach ($whyChecklist as $item)
                        <li class="flex items-center gap-3">
                            <span class="w-6 h-6 rounded-lg bg-primary/20 flex items-center justify-center text-primary text-sm">✓</span>
                            {{ $item }}
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </div>
                <div class="bg-primary/5 border border-primary/20 rounded-lg p-8">
                    @if (count($whyStats) > 0)
                        @php($firstStat = $whyStats[0])
                        <div class="text-center">
                            <p class="text-6xl font-display text-primary">{{ $firstStat['value'] ?? '' }}</p>
                            <p class="text-light-grey text-sm mt-2">{{ $firstStat['label'] ?? '' }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-6 mt-8">
                            @foreach (array_slice($whyStats, 1) as $stat)
                            <div class="text-center">
                                <p class="text-3xl font-display text-dark">{{ $stat['value'] ?? '' }}</p>
                                <p class="text-dark/40 text-xs mt-1">{{ $stat['label'] ?? '' }}</p>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- CTA --}}
    <div data-animate="fade-up" class="container mx-auto px-5 py-24 text-center">
        <h2 class="font-display text-3xl lg:text-5xl text-dark mb-6 uppercase">{{ $ctaHeading }}</h2>
        <p class="text-light-grey text-lg mb-8 max-w-xl mx-auto">
            {{ $ctaBody }}
        </p>
        <x-btn-primary href="{{ $ctaButtonUrl }}" label="{{ $ctaButtonText }}" />
    </div>
@endsection

@if (count($stepsItems) > 0)
    @push('scripts')
        <script type="application/ld+json">
            {!! json_encode([
                '@context' => 'https://schema.org',
                '@type' => 'HowTo',
                'name' => $stepsHeading,
                'description' => $stepsSubtitle,
                'step' => array_map(function ($step) {
                    return [
                        '@type' => 'HowToStep',
                        'position' => intval($step['step'] ?? 0),
                        'name' => $step['title'] ?? '',
                        'text' => $step['description'] ?? '',
                    ];
                }, $stepsItems),
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
        </script>
    @endpush
@endif
