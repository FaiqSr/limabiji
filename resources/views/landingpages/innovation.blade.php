@extends('layouts.landingpages')

@php
    $locale = app()->getLocale();
@endphp

@push('title', ($locale === 'id' ? 'Inovasi Proses Luwak Enzimatik' : 'Enzymatic Civet Coffee Innovation') . ' — Lima Biji Agritech')

@push('meta')
    <meta name="description" content="{{ __('landing.innovation_hero_subheading') }}">
    <meta property="og:title" content="{{ ($locale === 'id' ? 'Inovasi Proses Luwak Enzimatik' : 'Enzymatic Civet Coffee Innovation') . ' — Lima Biji Agritech' }}">
    <meta property="og:description" content="{{ __('landing.innovation_hero_subheading') }}">
    <meta property="og:type" content="website">
    <link rel="canonical" href="{{ url()->current() }}">
@endpush

@section('content')
{{-- 1. Hero Section --}}
<div class="container mx-auto px-5 pt-10 pb-10 lg:pt-5 lg:pb-5">
    <div class="max-w-5xl">
        <p data-animate="fade-up" class="text-sm lg:text-2xl font-bold tracking-[0.3em] uppercase text-light-grey mb-6">
            {{ __('landing.innovation_hero_label') }}
        </p>
        <h1 data-animate="fade-up" data-delay="0.1" class="font-display text-8xl sm:text-9xl lg:text-[12rem] text-dark leading-[0.85] uppercase">
            {!! __('landing.innovation_hero_heading') !!}
        </h1>
        <p data-animate="fade-up" data-delay="0.2" class="text-light-grey text-lg mt-8 max-w-xl">
            {{ __('landing.innovation_hero_subheading') }}
        </p>
    </div>
</div>

{{-- 2. Process Steps Section --}}
@php
    $steps = $locale === 'id' ? [
        [
            'step' => '01',
            'title' => 'Pengadaan Ceri Etis',
            'image' => 'https://images.unsplash.com/photo-1587734195503-904fca47e0e9?q=80&w=800&auto=format&fit=crop',
            'description' => 'Kami bermitra langsung dengan petani kecil dataran tinggi di seluruh Jawa Barat, Toraja, dan Aceh. Hanya ceri merah 100% matang yang dipetik tangan dengan kadar gula di atas 20° Brix yang diterima.',
            'details' => 'Petik tangan ceri merah, Kadar gula 20°+ Brix, Dataran tinggi (1200m+)',
        ],
        [
            'step' => '02',
            'title' => 'Isolasi & Formulasi Enzim',
            'image' => 'https://images.unsplash.com/photo-1532094349884-543bc11b234d?q=80&w=800&auto=format&fit=crop',
            'description' => 'Ilmuwan bio kami mengisolasi enzim proteolitik dan lipolitik alami yang mencerminkan biokimia pencernaan Musang Luwak Asia (Paradoxurus hermaphroditus).',
            'details' => 'Enzim berbasis tanaman, Proses bio-identik, Tanpa keterlibatan hewan',
        ],
        [
            'step' => '03',
            'title' => 'Fermentasi Terkontrol',
            'image' => 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?q=80&w=800&auto=format&fit=crop',
            'description' => 'Biji yang telah dikupas menjalani bio-fermentasi 36 jam dalam bioreaktor stainless steel. Suhu, pH, dan konsentrasi enzim dipantau secara real-time.',
            'details' => 'Durasi 36 jam, Kontrol pH real-time, Tangki stainless steel',
        ],
        [
            'step' => '04',
            'title' => 'Pencucian & Pengeringan Matahari',
            'image' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?q=80&w=800&auto=format&fit=crop',
            'description' => 'Biji dicuci menyeluruh dengan air mata air pegunungan untuk menghentikan fermentasi, lalu dikeringkan di atas bedengan surya untuk mencapai kadar air optimal 11%.',
            'details' => 'Cuci air mata air, Pengeringan bedengan, Target kadar air 11%',
        ],
        [
            'step' => '05',
            'title' => 'Penyimpanan & Kondisioning',
            'image' => 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?q=80&w=800&auto=format&fit=crop',
            'description' => 'Kopi parchment disimpan dalam gudang dengan suhu terkontrol selama 30–60 hari. Ini memungkinkan prekursor rasa stabil sebelum pengupasan.',
            'details' => 'Istirahat 30–60 hari, Kontrol iklim, Penuaan parchment',
        ],
        [
            'step' => '06',
            'title' => 'Grading Kualitas & Ekspor',
            'image' => 'https://images.unsplash.com/photo-1587080413959-06b859fb107d?q=80&w=800&auto=format&fit=crop',
            'description' => 'Setiap lot menjalani cupping, penyortiran densitas, dan sizing. Hanya biji dengan skor 84+ yang disetujui untuk ekspor. Dikemas vakum dalam kantong GrainPro food-grade.',
            'details' => 'Skor SCA: 84+, Screen: 16–18, Kemasan GrainPro',
        ],
    ] : [
        [
            'step' => '01',
            'title' => 'Ethical Cherry Sourcing',
            'image' => 'https://images.unsplash.com/photo-1587734195503-904fca47e0e9?q=80&w=800&auto=format&fit=crop',
            'description' => 'We partner directly with high-altitude smallholder farms across West Java, Toraja, and Aceh. Only 100% ripe, hand-picked red cherries with sugar levels above 20° Brix are accepted.',
            'details' => 'Hand-picked red cherries, 20°+ Brix sugar level, High altitude (1200m+)',
        ],
        [
            'step' => '02',
            'title' => 'Enzyme Isolation & Formulation',
            'image' => 'https://images.unsplash.com/photo-1532094349884-543bc11b234d?q=80&w=800&auto=format&fit=crop',
            'description' => 'Our bio-scientists isolate natural proteolytic and lipolytic enzymes that mirror the digestive biochemistry of the Asian Palm Civet (Paradoxurus hermaphroditus).',
            'details' => 'Plant-based enzymes, Bio-identical process, Zero animal involvement',
        ],
        [
            'step' => '03',
            'title' => 'Controlled Fermentation',
            'image' => 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?q=80&w=800&auto=format&fit=crop',
            'description' => 'Depulped beans undergo a 36-hour bio-fermentation in stainless steel bioreactors. Temperature, pH, and enzyme concentration are monitored in real time.',
            'details' => '36-hour duration, Real-time pH control, Stainless steel tanks',
        ],
        [
            'step' => '04',
            'title' => 'Washing & Solar Drying',
            'image' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?q=80&w=800&auto=format&fit=crop',
            'description' => 'Beans are thoroughly washed with mountain spring water to stop fermentation, then dried on raised solar beds to achieve an optimal 11% moisture content.',
            'details' => 'Spring water wash, Raised bed drying, 11% target moisture',
        ],
        [
            'step' => '05',
            'title' => 'Resting & Conditioning',
            'image' => 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?q=80&w=800&auto=format&fit=crop',
            'description' => 'Parchment coffee rests in temperature-controlled warehouses for 30–60 days. This allows flavor precursors to stabilize before hulling.',
            'details' => '30–60 days rest, Climate-controlled, Parchment aging',
        ],
        [
            'step' => '06',
            'title' => 'Quality Grading & Export',
            'image' => 'https://images.unsplash.com/photo-1587080413959-06b859fb107d?q=80&w=800&auto=format&fit=crop',
            'description' => 'Every lot undergoes cupping, density sorting, and screen sizing. Only beans scoring 84+ points are approved for export. Vacuum-packed in food-grade GrainPro bags.',
            'details' => 'SCA score: 84+, Screen: 16–18, GrainPro packed',
        ],
    ];
@endphp

<div class="container mx-auto px-5 py-24">
    <x-section-heading :title="__('landing.innovation_steps_title')" :subtitle="__('landing.innovation_steps_subtitle')" />

    <div data-animate="fade-up" class="space-y-24">
        @foreach ($steps as $index => $step)
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 items-center">
                {{-- Image --}}
                <div class="{{ $index % 2 === 1 ? 'lg:order-2' : '' }} relative">
                    <div class="rounded-lg overflow-hidden h-80 lg:h-96 bg-surface-alt border border-border">
                        @if(!empty($step['image']))
                            <img src="{{ $step['image'] }}" alt="{{ $step['title'] }}"
                                class="w-full h-full object-cover hover:scale-105 transition-transform duration-700">
                        @endif
                    </div>
                    <div class="absolute -bottom-4 -left-4 bg-primary text-dark w-16 h-16 rounded-lg flex items-center justify-center font-display text-2xl shadow-lg">
                        {{ $step['step'] }}
                    </div>
                </div>

                {{-- Content --}}
                <div class="{{ $index % 2 === 1 ? 'lg:order-1' : '' }}">
                    <h3 class="text-dark font-display text-3xl lg:text-4xl mb-4">{{ $step['title'] }}</h3>
                    <p class="text-light-grey text-base leading-relaxed mb-6">{!! $step['description'] !!}</p>
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
        ['value' => '84+', 'label' => __('landing.innovation_matters_stats_cupping')],
        ['value' => '0', 'label' => __('landing.innovation_matters_stats_animals')],
        ['value' => $locale === 'id' ? '48–72j' : '48–72h', 'label' => __('landing.innovation_matters_stats_fermentation')],
        ['value' => '6', 'label' => __('landing.innovation_matters_stats_quality')],
        ['value' => '7+', 'label' => __('landing.innovation_matters_stats_export')],
    ];
@endphp

<div class="container mx-auto px-5 py-24">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">
        {{-- Left: Text + Checklist --}}
        <div>
            <h2 class="font-display text-4xl sm:text-5xl text-dark uppercase leading-tight mb-6">
                {{ __('landing.innovation_matters_heading') }}
            </h2>
            <p class="text-dark/70 text-base leading-relaxed mb-4">
                {{ __('landing.innovation_matters_body') }}
            </p>
            <p class="text-dark/70 text-base leading-relaxed mb-6">
                {{ __('landing.innovation_matters_body2') }}
            </p>
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
        </div>

        {{-- Right: Stats Grid --}}
        <div class="grid grid-cols-2 gap-6">
            @foreach($mattersStats as $stat)
            <div class="card-solid p-6 text-center">
                <p class="font-display text-5xl text-dark">{{ $stat['value'] }}</p>
                <p class="text-dark/40 text-sm mt-2">{{ $stat['label'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- 4. CTA Card --}}
<div class="container mx-auto px-5 py-24">
    <div data-animate="scale-in" class="card-solid p-10 sm:p-16 relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 opacity-5 pointer-events-none select-none">
            <span class="font-display text-[12rem] text-dark leading-none">5</span>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center relative z-10">
            <div class="lg:col-span-7">
                <h2 class="font-display text-3xl sm:text-4xl lg:text-5xl text-dark leading-tight uppercase">
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
<script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'HowTo',
        'name' => __('landing.innovation_steps_title'),
        'description' => __('landing.innovation_steps_subtitle'),
        'step' => array_map(function ($step, $idx) {
            return [
                '@type' => 'HowToStep',
                'position' => intval($step['step']),
                'name' => $step['title'],
                'text' => strip_tags($step['description']),
            ];
        }, $steps, array_keys($steps)),
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush
