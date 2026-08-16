@extends('layouts.landingpages')

@push('title', $origin['name'] . ' Coffee Origin Gallery — Lima Biji Agritech')

@php($overview = is_object($origin) && method_exists($origin, 'getOverviewForLocale') ? $origin->getOverviewForLocale(app()->getLocale()) : ($origin['overview'] ?? ''))
@php($description = strip_tags($overview . ' ' . ($origin['province'] ?? '')))
@php($description = strlen($description) > 160 ? substr($description, 0, 160) : $description)

@php($flavorNotes = is_array($origin['flavor'] ?? null) ? $origin['flavor'] : (is_string($origin['flavor'] ?? null) ? (json_decode($origin['flavor'], true) ?: array_map('trim', explode(',', $origin['flavor']))) : []))

@php($farmList = is_array($origin['farms'] ?? null) ? $origin['farms'] : (is_string($origin['farms'] ?? null) ? (json_decode($origin['farms'], true) ?: array_map('trim', explode(',', $origin['farms']))) : []))

@php($galleryImages = is_array($origin['gallery'] ?? null) ? $origin['gallery'] : (is_string($origin['gallery'] ?? null) ? (json_decode($origin['gallery'], true) ?: []) : []))
@php($galleryImages = array_values(array_filter($galleryImages)))

@push('meta')
    <meta name="description" content="{{ $description }}">
    <meta property="og:title" content="{{ $origin['name'] }} Coffee Origin — Lima Biji Agritech">
    <meta property="og:description" content="{{ $description }}">
    @if($origin['image'] ?? null)
        <meta property="og:image" content="{{ $origin['image'] }}">
    @endif
@endpush

@if (count($galleryImages) > 0)
    @push('scripts')
        <script type="application/ld+json">
            {!! json_encode([
                '@context' => 'https://schema.org',
                '@type' => 'ItemList',
                'name' => $origin['name'] . ' Coffee Origin Gallery',
                'description' => $description,
                'itemListElement' => array_map(fn ($image, $index) => [
                    '@type' => 'ListItem',
                    'position' => $index + 1,
                    'item' => [
                        '@type' => 'ImageObject',
                        'contentUrl' => $image,
                        'caption' => $origin['name'] . ' coffee origin in ' . $origin['province'],
                    ],
                ], $galleryImages, array_keys($galleryImages)),
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
        </script>
    @endpush
@endif

@section('content')
    {{-- Hero — Full-Bleed Image + City Name --}}
    <div class="relative h-[70vh] min-h-[500px] mx-5 my-5 rounded-lg overflow-hidden bg-surface-alt">
        @if(!empty($origin['image']))
            <img src="{{ $origin['image'] }}"
                 alt="{{ $origin['name'] }} coffee origin in {{ $origin['province'] }}"
                 class="w-full h-full object-cover">
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-dark via-dark/40 to-transparent"></div>
        <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-5">
            <p data-animate="fade-up" class="text-sm font-bold tracking-[0.3em] uppercase text-white mb-4">{{ $origin['province'] }}</p>
            <h1 data-animate="fade-up" data-delay="0.1" class="font-display text-7xl sm:text-9xl lg:text-[10rem] text-white leading-[0.85] uppercase">
                {{ $origin['name'] }}
            </h1>
            <p data-animate="fade-up" data-delay="0.2" class="text-white/90 text-lg mt-6 max-w-xl">
                {{ $overview }}
            </p>
        </div>
        {{-- Scroll Indicator --}}
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
            <svg class="w-6 h-6 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
            </svg>
        </div>
    </div>

    {{-- Key Stats — Glassmorphism Grid --}}
    <div class="container mx-auto px-5 py-24">
        <div data-animate="scale-in" class="card-solid p-10 sm:p-16">
            <div class="flex flex-wrap justify-center gap-8 text-center">
                @if(!empty($origin['altitude']))
                <div class="min-w-[100px]">
                    <p class="text-3xl font-display text-white">{{ $origin['altitude'] }}</p>
                    <p class="text-light-grey text-xs mt-2 uppercase tracking-wider">Altitude</p>
                </div>
                @endif
                @if(!empty($origin['score']))
                <div class="min-w-[100px]">
                    <p class="text-3xl font-display text-white">{{ $origin['score'] }}</p>
                    <p class="text-light-grey text-xs mt-2 uppercase tracking-wider">SCA Score</p>
                </div>
                @endif
                @if(!empty($origin['process']))
                <div class="min-w-[100px]">
                    <p class="text-3xl font-display text-white">{{ $origin['process'] }}</p>
                    <p class="text-light-grey text-xs mt-2 uppercase tracking-wider">Process</p>
                </div>
                @endif
                @if(!empty($origin['harvest']))
                <div class="min-w-[100px]">
                    <p class="text-3xl font-display text-white">{{ $origin['harvest'] }}</p>
                    <p class="text-light-grey text-xs mt-2 uppercase tracking-wider">Harvest</p>
                </div>
                @endif
                @if(!empty($origin['varietals']))
                <div class="min-w-[100px]">
                    <p class="text-3xl font-display text-white">{{ $origin['varietals'] }}</p>
                    <p class="text-light-grey text-xs mt-2 uppercase tracking-wider">Varietals</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    @if (count($galleryImages) > 0)
        <div class="container mx-auto px-5 py-24">
            <div data-animate="fade-up" class="mb-10">
                <p class="section-heading">Origin Gallery</p>
                <h2 class="font-display text-4xl sm:text-5xl text-white uppercase mt-2">{{ $origin['name'] }} Gallery</h2>
            </div>
            <div data-animate="stagger" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach ($galleryImages as $image)
                    <figure class="stagger-item group aspect-[4/3] overflow-hidden rounded-lg bg-surface-alt border border-border">
                        <img src="{{ $image }}"
                             alt="{{ $origin['name'] }} coffee origin gallery image {{ $loop->iteration }} in {{ $origin['province'] }}"
                             loading="lazy"
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    </figure>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Flavor Profile --}}
    @if (count($flavorNotes) > 0)
    <div class="container mx-auto px-5 py-24">
        <div data-animate="fade-up" class="max-w-3xl">
            <p class="section-heading">Flavor Profile</p>
            <h2 class="font-display text-4xl sm:text-5xl text-white uppercase mt-2 mb-8">Tasting Notes</h2>
            <div class="flex flex-wrap gap-3">
                @foreach ($flavorNotes as $note)
                    <span class="text-sm font-medium text-white bg-surface-alt border border-border px-5 py-2.5 rounded-lg hover:border-primary/40 hover:bg-primary/10 transition-all duration-300">
                        {{ $note }}
                    </span>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- Partner Farms --}}
    @if (count($farmList) > 0)
    <div class="container mx-auto px-5 py-24">
        <div data-animate="fade-up" class="max-w-3xl">
            <p class="section-heading">Partner Farms</p>
            <h2 class="font-display text-4xl sm:text-5xl text-white uppercase mt-2 mb-8">Where We Source</h2>
            <div class="space-y-4">
                @foreach ($farmList as $farm)
                    <div class="flex items-center gap-4 p-5 rounded-lg bg-surface-alt border border-border hover:border-primary/30 transition-all duration-300">
                        <span class="w-3 h-3 rounded-full bg-secondary flex-shrink-0"></span>
                        <span class="text-white/90 font-medium">{{ $farm }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- CTA --}}
    <div class="container mx-auto px-5 py-24">
        <div data-animate="scale-in" class="card-solid p-10 sm:p-16 text-center">
            <h2 class="font-display text-3xl sm:text-4xl lg:text-5xl text-white uppercase mb-4">
                Interested in {{ $origin['name'] }}?
            </h2>
            <p class="text-light-grey text-base max-w-xl mx-auto mb-8">
                Request sample lots from our {{ $origin['name'] }} partner farms. Explore single-origin micro-lots processed with our enzymatic fermentation.
            </p>
            <x-btn-primary href="{{ url('/contact') }}" label="Request Samples" />
        </div>
    </div>
@endsection