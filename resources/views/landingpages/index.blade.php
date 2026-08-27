@extends('layouts.landingpages')

@php
    $locale = app()->getLocale();
@endphp

@push('title', 'Lima Biji Agritech — ' . ($locale === 'id' ? 'Kopi Luwak Enzimatik Specialty' : 'Specialty Enzymatic
    Civet Coffee'))

    @push('meta')
        <meta name="description" content="{{ __('landing.hero_subheading') }}">
        <meta property="og:title"
            content="Lima Biji Agritech — {{ $locale === 'id' ? 'Kopi Luwak Enzimatik Specialty' : 'Specialty Enzymatic Civet Coffee' }}">
        <meta property="og:description" content="{{ __('landing.hero_subheading') }}">
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:image" content="{{ asset('favicon.ico') }}">
        <meta name="twitter:title"
            content="Lima Biji Agritech — {{ $locale === 'id' ? 'Kopi Luwak Enzimatik Specialty' : 'Specialty Enzymatic Civet Coffee' }}">
        <meta name="twitter:description" content="{{ __('landing.hero_subheading') }}">
        <meta name="twitter:image" content="{{ asset('favicon.ico') }}">
        <link rel="canonical" href="{{ url()->current() }}">
    @endpush

    @push('schema')
        <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => 'Organization',
                    '@id' => url('/') . '#organization',
                    'name' => 'Lima Biji Agritech',
                    'url' => url('/'),
                    'logo' => asset('favicon.ico'),
                    'description' => __('landing.hero_subheading'),
                    'sameAs' => [],
                ],
                [
                    '@type' => 'WebSite',
                    '@id' => url('/') . '#website',
                    'url' => url('/'),
                    'name' => 'Lima Biji Agritech',
                    'publisher' => [
                        '@id' => url('/') . '#organization',
                    ],
                    'inLanguage' => $locale,
                ],
            ],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
    @endpush

    @push('modules')
        {{-- Swiper Bundle for Origins/Testimonials --}}
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@14.0.1/swiper-bundle.min.css" />
        <script src="https://cdn.jsdelivr.net/npm/swiper@14.0.1/swiper-bundle.min.js"></script>
        <script src="https://unpkg.com/ol/dist/ol.js"></script>
        <link rel="stylesheet" href="https://unpkg.com/ol/ol.css" />
        <script src="https://unpkg.com/ol-mapbox-style/dist/olms.js"></script>
    @endpush

    @push('styles')
        <style>
            #map {
                width: 100%;
                height: 480px;
                border-radius: 20px;
                overflow: hidden;
                box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08);
            }

            .ol-popup {
                position: absolute;
                background-color: #233F39;
                color: #FFFFFF;
                box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.4);
                padding: 16px;
                border-radius: 16px;
                border: 1px solid #2D4E46;
                bottom: 20px;
                left: -120px;
                width: 260px;
                pointer-events: auto;
                z-index: 100;
            }

            .ol-popup:after,
            .ol-popup:before {
                top: 100%;
                border: solid transparent;
                content: " ";
                height: 0;
                width: 0;
                position: absolute;
                pointer-events: none;
            }

            .ol-popup:after {
                border-top-color: #233F39;
                border-width: 10px;
                left: 120px;
                margin-left: -10px;
            }

            .ol-popup-closer {
                text-decoration: none;
                position: absolute;
                top: 8px;
                right: 12px;
                color: #A3B8B2;
                font-weight: bold;
                font-size: 16px;
            }

            .ol-popup-closer:hover {
                color: #FFFFFF;
            }

            .reset-view-btn {
                position: absolute;
                top: 15px;
                right: 15px;
                z-index: 10;
                background-color: #233F39;
                color: #FFFFFF;
                border: 1px solid #2D4E46;
                padding: 12px 14px;
                border-radius: 12px;
                font-size: 13px;
                font-weight: 600;
                cursor: pointer;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.25);
                display: flex;
                align-items: center;
                gap: 6px;
                transition: all 0.2s ease;
            }

            .reset-view-btn:hover {
                background-color: #069F80;
                border-color: #069F80;
                color: #FFFFFF;
                transform: translateY(-1px);
            }
        </style>
    @endpush

    @section('content')
        <div class="container mx-auto px-5 pt-10 pb-12 lg:pt-12 lg:pb-16">

            <div class="max-w-5xl">
                <p data-animate="fade-up"
                    class="text-xs sm:text-sm lg:text-base font-bold tracking-[0.3em] uppercase text-primary mb-6">
                    {{ __('landing.hero_label') }}
                </p>
                <h1 data-animate="fade-up" data-delay="0.1"
                    class="font-display text-7xl sm:text-9xl lg:text-[11rem] text-white leading-[0.85] uppercase">
                    {!! __('landing.hero_heading') !!}
                </h1>
                <p data-animate="fade-up" data-delay="0.2"
                    class="text-light-grey text-base sm:text-lg mt-8 max-w-2xl leading-relaxed">
                    {{ __('landing.hero_subheading') }}
                </p>
                <div data-animate="fade-up" data-delay="0.3" class="flex sm:flex-row flex-col items-start gap-4 mt-10">
                    <x-btn-primary :href="url('/innovation')" :label="__('landing.cta_innovations')" />
                    <x-btn-outline :href="url('/about')" :label="__('nav.about')" />
                </div>
            </div>
        </div>


        {{-- 3. Feature Overview & Key Stats Section --}}
        <div data-animate="fade-up" class="container mx-auto px-5 py-16 lg:py-24">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 items-center">
                {{-- Left Content --}}
                <div class="lg:col-span-7 space-y-6">
                    <p class="text-xs font-bold tracking-[0.25em] text-primary uppercase">
                        {{ __('landing.home_feature_badge') }}
                    </p>
                    <h2 class="font-display text-4xl sm:text-5xl lg:text-6xl text-white leading-tight uppercase">
                        {{ __('landing.home_feature_heading') }}
                    </h2>
                    <p class="text-light-grey text-base sm:text-lg leading-relaxed max-w-xl">
                        {{ __('landing.home_feature_desc') }}
                    </p>
                    <div class="pt-2">
                        <x-btn-primary :href="url('/about')" :label="__('nav.about')" />
                    </div>
                </div>

                {{-- Right Stats Card --}}
                <div class="lg:col-span-5">
                    <div
                        class="card-solid p-8 sm:p-10 rounded-2xl border border-border flex flex-col justify-between shadow-xs">
                        <div class="pb-6 border-b border-border/70">
                            <p class="text-[11px] font-mono uppercase font-bold tracking-widest text-primary mb-1">Standard of
                                Excellence</p>
                            <h3 class="text-white font-bold text-lg sm:text-xl leading-snug">
                                {{ __('landing.home_feature_card_title') }}
                            </h3>
                            <p class="text-light-grey text-xs sm:text-sm mt-2 leading-relaxed">
                                {{ __('landing.home_feature_card_desc') }}
                            </p>
                        </div>

                        <div class="grid grid-cols-2 gap-6 pt-6">
                            <div>
                                <p class="text-white font-display text-4xl sm:text-5xl">7+</p>
                                <p class="text-light-grey text-xs uppercase tracking-wider font-semibold mt-1">
                                    {{ __('landing.stat_export_countries') }}</p>
                            </div>
                            <div>
                                <p class="text-white font-display text-4xl sm:text-5xl">10+</p>
                                <p class="text-light-grey text-xs uppercase tracking-wider font-semibold mt-1">
                                    {{ __('landing.stat_partner_farms') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 4. Coffee Origins Carousel Section --}}
        @if ($origins->isNotEmpty())
            <div data-animate="fade-up" class="container mx-auto px-5 pt-8">
                <x-section-heading :title="__('landing.origins_title')" :subtitle="__('landing.origins_subtitle')" />
            </div>

            <div>
                <div class="swiper swiper-card">
                    <div class="swiper-wrapper">
                        @foreach ($origins as $origin)
                            @php
                                $originName = $origin->name;
                                $originSlug = $origin->slug ?: \Illuminate\Support\Str::slug($origin->name);
                                $originImage = $origin->image;
                            @endphp
                            <div class="swiper-slide !w-[80vw] sm:!w-[30rem] lg:!w-[40rem]">
                                <a href="{{ route('landingpages.origins', $originSlug) }}"
                                    class="group block h-[25rem] sm:h-[30rem] rounded-2xl overflow-hidden relative mb-5 bg-surface-alt border border-border">
                                    @if ($originImage)
                                        <img src="{{ $originImage }}" alt="{{ $originName }}" loading="lazy"
                                            decoding="async"
                                            class="w-full h-full object-cover group-hover:scale-125 transition-all duration-500">
                                    @endif
                                    <p
                                        class="absolute bottom-5 left-8 text-4xl font-display text-white py-2 transition-all duration-500 ease-in-out
                            group-hover:bottom-1/2 group-hover:left-1/2 group-hover:-translate-x-1/2 group-hover:scale-150
                            group-hover:translate-y-1/2 pointer-events-none">
                                        {{ $originName }}
                                    </p>
                                </a>
                            </div>
                        @endforeach
                    </div>
                    <div class="container mx-auto mb-5">
                        <div class="swiper-scrollbar !relative !bottom-0 mt-6 !h-4 hover:cursor-pointer border-dark rounded-lg">
                        </div>
                    </div>
                </div>
            </div>
        @endif


        {{-- 2. Export Map Section --}}
        @php
            $mapLocations = $exportDestinations
                ->map(function ($dest) use ($locale) {
                    return [
                        'name' => $dest->getNameForLocale($locale),
                        'code' => $dest->country_code,
                        'coords' => [$dest->longitude, $dest->latitude],
                        'description' => $dest->getDescriptionForLocale($locale),
                    ];
                })
                ->values()
                ->all();
        @endphp
        <div class="container mx-auto px-5 relative my-16 lg:my-24">
            <x-section-heading :title="__('landing.export_map_title')" :subtitle="__('landing.export_map_subtitle')" />
            <div class="relative mt-8">
                <div id="map"></div>

                <button id="btn-reset-map" class="reset-view-btn" title="Kembali ke posisi awal">
                    <svg xmlns="https://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8" />
                        <path d="M3 3v5h5" />
                    </svg>
                </button>

                <div id="popup" class="ol-popup">
                    <a href="#" id="popup-closer" class="ol-popup-closer">&times;</a>
                    <div id="popup-content"></div>
                </div>
            </div>
        </div>


        {{-- 6. Testimonials Section --}}
        @if ($testimonials->isNotEmpty())
            <div data-animate="fade-up" class="container mx-auto px-5 py-20 lg:py-24">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-12 gap-4">
                    <x-section-heading :title="__('landing.testimonials_title')" :subtitle="__('landing.testimonials_subheading')" />
                    <a href="{{ url('/testimonials') }}"
                        class="group flex items-center gap-2 text-white font-bold hover:text-primary transition-colors duration-300">
                        <span>{{ __('landing.testimonials_view_all') }}</span>
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform duration-300" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                </div>

                <div data-animate="stagger" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($testimonials as $testimonial)
                        <div class="stagger-item">
                            <x-testimonial-card :testimonial="$testimonial" />
                        </div>
                    @endforeach
                </div>
            </div>
        @endif


        {{-- 7. FAQs Section --}}
        @if ($faqs->isNotEmpty())
            <div class="container mx-auto px-5 py-20 lg:py-24">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
                    <div>
                        <x-section-heading :title="__('landing.faq_title')" :subtitle="__('landing.faq_subtitle')" />
                    </div>

                    <div>
                        @foreach ($faqs as $faq)
                            <details data-animate="fade-up"
                                class="faq-item group border-y border-border transition-colors duration-300 hover:border-primary/30 [&[open]]:border-primary/50 [&[open]]:bg-primary/5 overflow-hidden">
                                <summary
                                    class="flex justify-between items-center cursor-pointer p-6 sm:p-8 font-bold text-white text-lg sm:text-xl select-none list-none gap-4">
                                    <span>{{ $faq->getQuestionForLocale($locale) }}</span>
                                    <span
                                        class="faq-icon flex-shrink-0 w-10 h-10 rounded-lg bg-primary/10 border border-primary/20 group-hover:border-primary flex items-center justify-center text-primary transition-transform duration-300">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </span>
                                </summary>
                                <div
                                    class="faq-content grid grid-rows-[0fr] transition-[grid-template-rows] duration-300 ease-out">
                                    <div class="overflow-hidden">
                                        <div
                                            class="px-6 sm:px-8 pb-6 sm:pb-8 pt-0 text-white/80 text-sm sm:text-base leading-relaxed border-t border-border mt-2 pt-4">
                                            {!! $faq->getAnswerForLocale($locale) !!}
                                        </div>
                                    </div>
                                </div>
                            </details>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        {{-- 5. News & Stories Section --}}
        @if ($articles->isNotEmpty())
            <div data-animate="fade-up" class="container mx-auto px-5 py-20 lg:py-24">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-8 gap-4">
                    <x-section-heading :title="__('landing.news_title')" :subtitle="__('landing.news_subtitle')" />
                    <a href="{{ url('/news') }}"
                        class="group flex items-center gap-2 text-white font-bold hover:text-primary transition-colors duration-300">
                        <span>{{ __('landing.news_view_all') }}</span>
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform duration-300" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                </div>

                <div data-animate="stagger" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($articles as $news)
                        <div class="stagger-item">
                            <x-news-card :news="$news" />
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- 8. CTA Banner --}}
        <div class="container mx-auto px-5 py-16 lg:py-24">
            <div data-animate="scale-in" class="card-solid p-10 sm:p-16 relative overflow-hidden">
                <div class="absolute -right-10 -bottom-10 opacity-5 pointer-events-none select-none">
                    <span class="font-display text-[12rem] text-white leading-none">5</span>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center relative z-10">
                    <div class="lg:col-span-7">
                        <h2 class="font-display text-3xl sm:text-4xl lg:text-5xl text-white leading-tight uppercase">
                            {{ __('landing.cta_home_heading') }}
                        </h2>
                        <p class="text-light-grey text-base leading-relaxed mb-8">
                            {{ __('landing.cta_home_body') }}
                        </p>
                    </div>
                    <div class="lg:col-span-5">
                        <x-btn-primary :href="url('/contact')" :label="__('landing.cta_home_btn')" class="w-full" />
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @push('scripts')
        <script>
            // OpenLayers Map Initialization
            (function() {
                const initialCenter = [70.0, 10.0];
                const initialZoom = 2.3;

                const openfreemap = new ol.layer.Group();
                const locations = {!! json_encode($mapLocations) !!};

                const features = locations.map(loc => {
                    const feature = new ol.Feature({
                        geometry: new ol.geom.Point(ol.proj.fromLonLat(loc.coords)),
                        name: loc.name,
                        description: loc.description,
                    });
                    feature.setStyle(new ol.style.Style({
                        image: new ol.style.Icon({
                            anchor: [0.5, 0.5],
                            src: `https://flagcdn.com/w40/${loc.code}.png`,
                            scale: 0.85,
                        }),
                    }));
                    return feature;
                });

                const coffeeLayer = new ol.layer.Vector({
                    source: new ol.source.Vector({
                        features: features
                    }),
                });

                const controls = typeof ol.control.defaults === 'function' ?
                    ol.control.defaults({
                        zoom: false
                    }) :
                    ol.control.defaults.defaults({
                        zoom: false
                    });

                const container = document.getElementById('popup');
                const content = document.getElementById('popup-content');
                const closer = document.getElementById('popup-closer');

                if (!container || !content || !closer) return;

                const overlay = new ol.Overlay({
                    element: container,
                    autoPan: {
                        animation: {
                            duration: 250
                        }
                    },
                });

                closer.onclick = function() {
                    overlay.setPosition(undefined);
                    closer.blur();
                    return false;
                };

                const view = new ol.View({
                    center: ol.proj.fromLonLat(initialCenter),
                    zoom: initialZoom,
                    minZoom: 1.5,
                });

                const mapEl = document.getElementById('map');
                if (!mapEl) return;

                const map = new ol.Map({
                    controls: controls,
                    layers: [openfreemap, coffeeLayer],
                    overlays: [overlay],
                    view: view,
                    target: 'map',
                });

                olms.apply(openfreemap,
                    'https://api.maptiler.com/maps/019ff310-e37e-78d9-a360-edec8b678276/style.json?key=F7vo4PTXSgkVkQxxM3gY'
                );

                map.on('singleclick', function(evt) {
                    const feature = map.forEachFeatureAtPixel(evt.pixel, function(feat) {
                        return feat;
                    });
                    if (feature) {
                        const coordinates = feature.getGeometry().getCoordinates();
                        const name = feature.get('name');
                        const description = feature.get('description');
                        content.innerHTML =
                            `<h3 class="font-bold text-lg text-primary mb-1">${name}</h3><p class="text-sm text-gray-300 leading-snug">${description}</p>`;
                        overlay.setPosition(coordinates);
                    } else {
                        overlay.setPosition(undefined);
                    }
                });

                map.on('pointermove', function(e) {
                    const pixel = map.getEventPixel(e.originalEvent);
                    const hit = map.hasFeatureAtPixel(pixel);
                    map.getTargetElement().style.cursor = hit ? 'pointer' : '';
                });

                const resetBtn = document.getElementById('btn-reset-map');
                if (resetBtn) {
                    resetBtn.addEventListener('click', function() {
                        overlay.setPosition(undefined);
                        view.animate({
                            center: ol.proj.fromLonLat(initialCenter),
                            zoom: initialZoom,
                            duration: 600
                        });
                    });
                }
            })();

            // Swiper Origins Carousel
            document.addEventListener('DOMContentLoaded', function() {
                function getContainerOffset() {
                    const container = document.querySelector('.container');
                    if (!container) return 20;
                    const windowWidth = window.innerWidth;
                    const containerWidth = container.getBoundingClientRect().width;
                    const padding = 20;
                    const offset = ((windowWidth - containerWidth) / 2) + padding;
                    return Math.max(offset, padding);
                }

                const dynamicOffset = getContainerOffset();

                const swiperEl = document.querySelector('.swiper-card');
                if (swiperEl) {
                    const swiper = new Swiper('.swiper-card', {
                        slidesPerView: 'auto',
                        spaceBetween: 20,
                        loop: false,
                        slidesOffsetBefore: dynamicOffset,
                        slidesOffsetAfter: dynamicOffset,
                        grabCursor: true,
                        scrollbar: {
                            el: '.swiper-scrollbar',
                            draggable: true,
                            snapOnRelease: true,
                        },
                    });

                    window.addEventListener('resize', function() {
                        const newOffset = getContainerOffset();
                        swiper.params.slidesOffsetBefore = newOffset;
                        swiper.params.slidesOffsetAfter = newOffset;
                        swiper.update();
                    });
                }
            });

            // FAQs Accordion Interactivity
            document.querySelectorAll('.faq-item').forEach((details) => {
                const summary = details.querySelector('summary');
                const content = details.querySelector('.faq-content');
                const icon = details.querySelector('.faq-icon');

                summary.addEventListener('click', (e) => {
                    e.preventDefault();

                    if (details.open) {
                        content.classList.remove('grid-rows-[1fr]');
                        content.classList.add('grid-rows-[0fr]');
                        icon.classList.remove('rotate-180');

                        setTimeout(() => {
                            details.open = false;
                        }, 300);
                    } else {
                        details.open = true;
                        icon.classList.add('rotate-180');

                        requestAnimationFrame(() => {
                            content.classList.remove('grid-rows-[0fr]');
                            content.classList.add('grid-rows-[1fr]');
                        });
                    }
                });
            });
        </script>
    @endpush
