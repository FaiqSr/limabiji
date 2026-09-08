@extends('layouts.landingpages')

@php
    $locale = app()->getLocale();
    $statSca         = \App\Models\SiteSetting::get('stat_innovation_sca_score', null, '82+');
    $statExports     = \App\Models\SiteSetting::get('stat_export_destinations', null, '7+');
@endphp

@push('title', ($locale === 'id' ? 'Inovasi Proses Luwak Enzimatik' : 'Enzymatic Civet Coffee Innovation') . ' — Lima Biji Agritech')

@push('meta')
    <meta name="description" content="{{ __('landing.innovation_hero_subheading') }}">
    <meta property="og:title" content="{{ ($locale === 'id' ? 'Inovasi Proses Luwak Enzimatik' : 'Enzymatic Civet Coffee Innovation') . ' — Lima Biji Agritech' }}">
    <meta property="og:description" content="{{ __('landing.innovation_hero_subheading') }}">
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('favicon.ico') }}">
    <meta name="twitter:title" content="{{ ($locale === 'id' ? 'Inovasi Proses Luwak Enzimatik' : 'Enzymatic Civet Coffee Innovation') . ' — Lima Biji Agritech' }}">
    <meta name="twitter:description" content="{{ __('landing.innovation_hero_subheading') }}">
    <meta name="twitter:image" content="{{ asset('favicon.ico') }}">
    <link rel="canonical" href="{{ url()->current() }}">
@endpush

@push('schema')
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => 'HowTo',
                    '@id' => url()->current() . '#howto',
                    'name' => ($locale === 'id' ? 'Inovasi Proses Luwak Enzimatik' : 'Enzymatic Civet Coffee Innovation') . ' — Lima Biji Agritech',
                    'description' => __('landing.innovation_hero_subheading'),
                    'inLanguage' => $locale,
                    'totalTime' => 'P3D',
                ],
                [
                    '@type' => 'BreadcrumbList',
                    '@id' => url()->current() . '#breadcrumb',
                    'itemListElement' => [
                        [
                            '@type' => 'ListItem',
                            'position' => 1,
                            'name' => $locale === 'id' ? 'Beranda' : 'Home',
                            'item' => url('/'),
                        ],
                        [
                            '@type' => 'ListItem',
                            'position' => 2,
                            'name' => $locale === 'id' ? 'Inovasi' : 'Innovation',
                            'item' => url()->current(),
                        ],
                    ],
                ],
            ],
        ], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endpush

@section('content')
{{-- 1. Hero Section --}}
<div class="container mx-auto px-5 pt-10 pb-10 lg:pt-5 lg:pb-5">
    <div class="max-w-5xl">
        <p data-animate="fade-up" class="text-sm lg:text-2xl font-bold tracking-[0.3em] uppercase text-light-grey mb-6">
            {{ __('landing.innovation_hero_label') }}
        </p>
        <h1 data-animate="fade-up" data-delay="0.1" class="font-display text-8xl sm:text-9xl lg:text-[12rem] text-white leading-[0.85] uppercase">
            {!! __('landing.innovation_hero_heading') !!}
        </h1>
        <p data-animate="fade-up" data-delay="0.2" class="text-light-grey text-lg mt-8 max-w-xl">
            {{ __('landing.innovation_hero_subheading') }}
        </p>
    </div>
</div>

{{-- 2. Process Steps Section --}}
<div class="container mx-auto px-5 py-24">
    <x-section-heading :title="__('landing.innovation_steps_title')" :subtitle="__('landing.innovation_steps_subtitle')" />

    <div data-animate="fade-up" class="space-y-24">
        @foreach ($steps as $index => $step)
            @php
                $stepNumber = is_array($step) 
                    ? ($step['step'] ?? str_pad($index + 1, 2, '0', STR_PAD_LEFT)) 
                    : ($step->step_number ? str_pad($step->step_number, 2, '0', STR_PAD_LEFT) : str_pad($index + 1, 2, '0', STR_PAD_LEFT));
                $stepTitle = is_array($step) ? ($step['title'] ?? '') : $step->getTitleForLocale($locale);
                $stepDesc = is_array($step) ? ($step['description'] ?? '') : $step->getDescriptionForLocale($locale);
                $stepDetails = is_array($step) ? ($step['details'] ?? '') : $step->getDetailsForLocale($locale);
                $stepImage = is_array($step) ? ($step['image'] ?? '') : $step->image;
            @endphp
            <div class="step-card grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 items-center">
                {{-- Image --}}
                <div class="{{ $index % 2 === 1 ? 'lg:order-2' : '' }} relative">
                    <div class="rounded-lg overflow-hidden h-80 lg:h-96 bg-surface-alt border border-border">
                        @if(!empty($stepImage))
                            <img src="{{ $stepImage }}" alt="{{ $stepTitle }}"
                                loading="lazy" decoding="async"
                                class="w-full h-full object-cover hover:scale-105 transition-transform duration-700">
                        @endif
                    </div>
                    <div class="step-badge absolute -bottom-4 -left-4 bg-primary text-white w-16 h-16 rounded-lg flex items-center justify-center font-display text-2xl shadow-lg will-change-transform">
                        {{ $stepNumber }}
                    </div>
                </div>

                {{-- Content --}}
                <div class="{{ $index % 2 === 1 ? 'lg:order-1' : '' }}">
                    <h3 class="text-white font-display text-3xl lg:text-4xl mb-4">{{ $stepTitle }}</h3>
                    <p class="text-light-grey text-base leading-relaxed mb-6">{!! $stepDesc !!}</p>
                    @if(!empty($stepDetails))
                        <div class="flex flex-wrap gap-3">
                            @foreach (array_map('trim', explode(',', $stepDetails)) as $detail)
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

{{-- 3. Text With Stats Section --}}
@php
    $checklistItems = $locale === 'id' ? [
        'Bersumber etis dan 100% bebas eksploitasi',
        'Kualitas konsisten — setiap batch terasa sama',
        'Aman pangan dan diproses higienis',
        'Lebih rendah kepahitan, lebih tinggi kemanisan',
    ] : [
        'Ethically sourced and 100% cruelty-free',
        'Consistent in quality — every batch tastes the same',
        'Food-safe and hygienically processed',
        'Lower in bitterness, higher in sweetness',
    ];

    $mattersStats = [
        ['value' => $statSca,     'label' => __('landing.innovation_matters_stats_cupping')],
        ['value' => '0',          'label' => __('landing.innovation_matters_stats_animals')],
        ['value' => $locale === 'id' ? '48–72j' : '48–72h', 'label' => __('landing.innovation_matters_stats_fermentation')],
        ['value' => '6',          'label' => __('landing.innovation_matters_stats_quality')],
        ['value' => $statExports, 'label' => __('landing.innovation_matters_stats_export')],
    ];
@endphp

<div class="container mx-auto px-5 py-24">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">
        {{-- Left: Text + Checklist --}}
        <div>
            <h2 class="font-display text-4xl sm:text-5xl text-white uppercase leading-tight mb-6">
                {{ __('landing.innovation_matters_heading') }}
            </h2>
            <p class="text-white/80 text-base leading-relaxed mb-4">
                {{ __('landing.innovation_matters_body') }}
            </p>
            <p class="text-white/80 text-base leading-relaxed mb-6">
                {{ __('landing.innovation_matters_body2') }}
            </p>
            <ul class="space-y-2 mt-6">
                @foreach($checklistItems as $item)
                <li class="flex items-center gap-3 text-white/90 text-sm font-medium">
                    <span class="w-5 h-5 rounded-full bg-primary/10 border border-primary/30 flex items-center justify-center flex-shrink-0">
                        <svg class="w-3 h-3 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    </span>
                    {{ $item }}
                </li>
                @endforeach
            </ul>
        </div>

        {{-- Right: Stats Grid --}}
        <div class="grid grid-cols-2 gap-6">
            @foreach($mattersStats as $stat)
            <div class="card-solid p-6 text-center">
                <p class="font-display text-5xl text-white">{{ $stat['value'] }}</p>
                <p class="text-light-grey text-sm mt-2">{{ $stat['label'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- 4. CTA Card --}}
<div class="container mx-auto px-5 py-24">
    <div data-animate="scale-in" class="card-solid p-10 sm:p-16 relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 opacity-5 pointer-events-none select-none">
            <span class="font-display text-[12rem] text-white leading-none">5</span>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center relative z-10">
            <div class="lg:col-span-7">
                <h2 class="font-display text-3xl sm:text-4xl lg:text-5xl text-white leading-tight uppercase">
                    {{ __('landing.cta_innovation_heading') }}
                </h2>
            </div>
            <div class="lg:col-span-5">
                <p class="text-light-grey text-base leading-relaxed mb-8">
                    {{ __('landing.cta_innovation_body') }}
                </p>
                <x-btn-primary :href="url('/contact')" :label="__('landing.cta_innovation_btn')" />
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (typeof Motion !== 'undefined' && typeof Motion.scroll === 'function' && typeof Motion.animate === 'function') {
            const { scroll, animate } = Motion;
            document.querySelectorAll('.step-card').forEach((card) => {
                const badge = card.querySelector('.step-badge');
                if (badge) {
                    scroll(
                        animate(badge, { y: [0, -200] }, { duration: 1 }),
                        {
                            target: card,
                            offset: ["start center", "center start"]
                        }
                    );
                }
            });
        }
    });
</script>
<script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'HowTo',
        'name' => __('landing.innovation_steps_title'),
        'description' => __('landing.innovation_steps_subtitle'),
        'step' => collect($steps)->map(function ($step, $idx) use ($locale) {
            $num = is_array($step) ? intval($step['step'] ?? ($idx + 1)) : intval($step->step_number ?: ($idx + 1));
            $title = is_array($step) ? ($step['title'] ?? '') : $step->getTitleForLocale($locale);
            $desc = is_array($step) ? ($step['description'] ?? '') : $step->getDescriptionForLocale($locale);
            return [
                '@type' => 'HowToStep',
                'position' => $num,
                'name' => $title,
                'text' => strip_tags($desc),
            ];
        })->values()->all(),
    ], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush
