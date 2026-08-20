@extends('layouts.landingpages')

@php
    $locale = app()->getLocale();
@endphp

@push('title', ($locale === 'id' ? 'Tentang Kami' : 'About Us') . ' — Lima Biji Agritech')

@push('meta')
    <meta name="description" content="{{ __('landing.about_hero_subheading') }}">
    <meta property="og:title" content="{{ ($locale === 'id' ? 'Tentang Kami' : 'About Us') . ' — Lima Biji Agritech' }}">
    <meta property="og:description" content="{{ __('landing.about_hero_subheading') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('favicon.ico') }}">
    <meta name="twitter:title" content="{{ ($locale === 'id' ? 'Tentang Kami' : 'About Us') . ' — Lima Biji Agritech' }}">
    <meta name="twitter:description" content="{{ __('landing.about_hero_subheading') }}">
    <meta name="twitter:image" content="{{ asset('favicon.ico') }}">
    <link rel="canonical" href="{{ url()->current() }}">
@endpush

@push('schema')
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => 'AboutPage',
                    '@id' => url()->current() . '#webpage',
                    'url' => url()->current(),
                    'name' => ($locale === 'id' ? 'Tentang Kami' : 'About Us') . ' — Lima Biji Agritech',
                    'description' => __('landing.about_hero_subheading'),
                    'inLanguage' => $locale,
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
                            'name' => $locale === 'id' ? 'Tentang Kami' : 'About Us',
                            'item' => url()->current(),
                        ],
                    ],
                ],
            ],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endpush

@section('content')
    {{-- 1. Hero Section --}}
    <div class="container mx-auto px-5 pt-10 pb-10 lg:pt-5 lg:pb-5">
        <div class="max-w-5xl">
            <p data-animate="fade-up" class="text-sm lg:text-2xl font-bold tracking-[0.3em] uppercase text-light-grey mb-6">
                {{ __('landing.about_hero_label') }}
            </p>
            <h1 data-animate="fade-up" data-delay="0.1"
                class="font-display text-8xl sm:text-9xl lg:text-[12rem] text-white leading-[0.85] uppercase">
                {!! __('landing.about_hero_heading') !!}
            </h1>
            <p data-animate="fade-up" data-delay="0.2" class="text-light-grey text-lg mt-8 max-w-xl">
                {{ __('landing.about_hero_subheading') }}
            </p>
        </div>
    </div>

    {{-- 2. Our Story / Problem & Solution Section --}}
    <div data-animate="fade-up" class="container mx-auto px-5 py-24">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
            {{-- Text Column --}}
            <div class="lg:col-span-7 space-y-6">
                <div>
                    <p class="text-xs font-bold tracking-[0.25em] text-primary uppercase mb-2">
                        {{ __('landing.about_story_label') }}</p>
                    <h2 class="font-display text-4xl sm:text-5xl text-white leading-tight uppercase">
                        {{ __('landing.about_story_heading') }}
                    </h2>
                </div>
                <p class="text-light-grey text-base sm:text-lg leading-relaxed">
                    {{ __('landing.about_story_p1') }}
                </p>
                <p class="text-light-grey text-base sm:text-lg leading-relaxed">
                    {{ __('landing.about_story_p2') }}
                </p>
            </div>

            {{-- Visual Card Column --}}
            <div class="lg:col-span-5 relative">
                <div
                    class="rounded-2xl overflow-hidden h-[420px] bg-surface-alt border border-border relative group shadow-xl">
                    <img src="{{ asset('assets/images/limabiji/toko.webp') }}"
                        alt="Lima Biji specialty coffee cherry harvesting and research" loading="lazy" decoding="async"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-dark/90 via-dark/30 to-transparent"></div>

                    {{-- Floating Badges --}}
                    <div class="absolute bottom-6 left-6 right-6 flex items-center justify-between gap-4">
                        <div class="bg-card/95 backdrop-blur-xs p-3 rounded-xl border border-border text-xs shadow-md">
                            <p class="text-light-grey text-[10px] uppercase font-bold tracking-wider">Quality Benchmark</p>
                            <p class="font-display text-2xl text-white">82+ SCA</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. Vision & Mission Section --}}
    <div class="container mx-auto px-5 py-24">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            {{-- Vision Card --}}
            <div data-animate="fade-up" class="card-solid p-8 sm:p-12 flex flex-col justify-between">
                <div>
                    <div
                        class="w-14 h-14 rounded-2xl bg-primary/10 border border-primary/20 flex items-center justify-center text-primary mb-6">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </div>
                    <h3 class="font-display text-3xl sm:text-4xl text-white uppercase mb-4">
                        {{ __('landing.about_vision_title') }}</h3>
                    <p class="text-light-grey text-base sm:text-lg leading-relaxed italic">
                        &ldquo;{{ __('landing.about_vision_desc') }}&rdquo;
                    </p>
                </div>
                <div class="mt-8 pt-6 border-t border-border/50 text-xs font-mono uppercase text-light-grey/60">
                    Ethical Standard • Global Vision
                </div>
            </div>

            {{-- Mission Card --}}
            <div data-animate="fade-up" data-delay="0.1" class="card-solid p-8 sm:p-12 flex flex-col justify-between">
                <div>
                    <div
                        class="w-14 h-14 rounded-2xl bg-secondary/10 border border-secondary/20 flex items-center justify-center text-secondary mb-6">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h3 class="font-display text-3xl sm:text-4xl text-white uppercase mb-4">
                        {{ __('landing.about_mission_title') }}</h3>
                    <p class="text-light-grey text-base sm:text-lg leading-relaxed italic">
                        &ldquo;{{ __('landing.about_mission_desc') }}&rdquo;
                    </p>
                </div>
                <div class="mt-8 pt-6 border-t border-border/50 text-xs font-mono uppercase text-light-grey/60">
                    Actionable Commitment
                </div>
            </div>
        </div>
    </div>

    {{-- 3.5. Certificates & Compliance Section (Auto-detects Overflow -> Marquee, Else Centered) --}}
    @if (isset($certificates) && $certificates->isNotEmpty())
        <div id="cert-section" class="py-16 overflow-hidden relative opacity-0 transition-opacity duration-500">
            {{-- Side Fade Gradients for Seamless Look (visible on overflow marquee) --}}
            <div id="cert-fade-left"
                class="hidden absolute left-0 top-0 bottom-0 w-24 sm:w-48 z-10 pointer-events-none bg-gradient-to-r from-surface to-transparent">
            </div>
            <div id="cert-fade-right"
                class="hidden absolute right-0 top-0 bottom-0 w-24 sm:w-48 z-10 pointer-events-none bg-gradient-to-l from-surface to-transparent">
            </div>

            <div class="container mx-auto px-5 pb-16">
                <div class="text-center max-w-3xl mx-auto">
                    <p data-animate="fade-up" class="text-xs font-bold tracking-[0.25em] text-primary uppercase mb-2">
                        {{ __('landing.about_certificates_label') }}
                    </p>
                    <h2 data-animate="fade-up" data-delay="0.1"
                        class="font-display text-4xl sm:text-5xl text-white uppercase mb-4">
                        {{ __('landing.about_certificates_title') }}
                    </h2>
                    <p data-animate="fade-up" data-delay="0.2" class="text-light-grey text-base">
                        {{ __('landing.about_certificates_subtitle') }}
                    </p>
                </div>
            </div>

            {{-- Certificates Track Wrapper --}}
            <div id="cert-track-wrapper" class="w-full flex justify-center overflow-hidden">
                <div id="cert-track" class="flex items-center gap-12 sm:gap-16 justify-center flex-wrap px-5">
                    @foreach ($certificates as $cert)
                        <div
                            class="cert-item flex flex-col items-center justify-center gap-3 shrink-0 px-4 py-2 opacity-35 hover:opacity-100 grayscale hover:grayscale-0 transition-all duration-500 group cursor-default select-none">
                            {{-- Logo --}}
                            <div
                                class="w-50 h-50 sm:w-60 sm:h-60 flex items-center justify-center transition-transform duration-300 group-hover:scale-110">
                                <img src="{{ $cert->logo }}" alt="{{ $cert->name }}" loading="lazy"
                                    decoding="async" class="w-full h-full object-cover filter drop-shadow-sm">
                            </div>

                            {{-- Certificate Number --}}
                            <span
                                class="font-medium text-xs sm:text-sm text-light-grey/80 group-hover:text-primary font-medium tracking-tight transition-colors text-center">
                                {{ $cert->certificate_number }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        @push('scripts')
            <script>
                (function() {
                    const section = document.getElementById('cert-section');
                    const track = document.getElementById('cert-track');
                    const fadeLeft = document.getElementById('cert-fade-left');
                    const fadeRight = document.getElementById('cert-fade-right');

                    if (!section || !track) return;

                    const baseItemsHtml = track.innerHTML;

                    function adjustCertificatesLayout() {
                        // Test 1: Render single set non-wrapped to measure true natural width
                        track.className = 'flex items-center gap-12 sm:gap-16 flex-nowrap px-5';
                        track.innerHTML = baseItemsHtml;
                        fadeLeft.classList.add('hidden');
                        fadeRight.classList.add('hidden');

                        const contentWidth = track.scrollWidth;
                        const containerWidth = section.clientWidth;

                        if (contentWidth > containerWidth) {
                            // Overflows container -> Convert to infinite marquee with duplicate sets
                            track.innerHTML = baseItemsHtml + baseItemsHtml + baseItemsHtml;
                            track.className = 'animate-marquee items-center gap-12 sm:gap-16 flex';
                            fadeLeft.classList.remove('hidden');
                            fadeRight.classList.remove('hidden');
                        } else {
                            // Fits inside container -> Justify center nicely
                            track.className = 'flex items-center gap-12 sm:gap-16 justify-center flex-wrap px-5';
                        }

                        section.classList.remove('opacity-0');
                    }

                    // Run on load and on image loads / resize
                    if (document.readyState === 'complete') {
                        adjustCertificatesLayout();
                    } else {
                        window.addEventListener('load', adjustCertificatesLayout);
                    }

                    let resizeTimer;
                    window.addEventListener('resize', function() {
                        clearTimeout(resizeTimer);
                        resizeTimer = setTimeout(adjustCertificatesLayout, 150);
                    });
                })();
            </script>
        @endpush
    @endif

    {{-- 4. Core Values Section --}}
    <div class="container mx-auto px-5 py-24">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <p data-animate="fade-up" class="text-xs font-bold tracking-[0.25em] text-primary uppercase mb-2">
                {{ __('landing.about_values_label') }}
            </p>
            <h2 data-animate="fade-up" data-delay="0.1"
                class="font-display text-4xl sm:text-5xl text-white uppercase mb-4">
                {{ __('landing.about_values_title') }}
            </h2>
            <p data-animate="fade-up" data-delay="0.2" class="text-light-grey text-base">
                {{ __('landing.about_values_subtitle') }}
            </p>
        </div>

        <div data-animate="stagger" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- Value 1 --}}
            <div
                class="stagger-item card-solid p-8 rounded-2xl border border-border hover:border-primary/40 transition-all duration-300">
                <div
                    class="w-12 h-12 rounded-xl bg-primary/10 border border-primary/20 flex items-center justify-center text-primary mb-6">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>
                <h4 class="font-display text-2xl text-white uppercase mb-2">{{ __('landing.about_val_1_title') }}</h4>
                <p class="text-light-grey text-sm leading-relaxed">{{ __('landing.about_val_1_desc') }}</p>
            </div>

            {{-- Value 2 --}}
            <div
                class="stagger-item card-solid p-8 rounded-2xl border border-border hover:border-secondary/40 transition-all duration-300">
                <div
                    class="w-12 h-12 rounded-xl bg-secondary/10 border border-secondary/20 flex items-center justify-center text-secondary mb-6">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                    </svg>
                </div>
                <h4 class="font-display text-2xl text-white uppercase mb-2">{{ __('landing.about_val_2_title') }}</h4>
                <p class="text-light-grey text-sm leading-relaxed">{{ __('landing.about_val_2_desc') }}</p>
            </div>

            {{-- Value 3 --}}
            <div
                class="stagger-item card-solid p-8 rounded-2xl border border-border hover:border-primary/40 transition-all duration-300">
                <div
                    class="w-12 h-12 rounded-xl bg-primary/10 border border-primary/20 flex items-center justify-center text-primary mb-6">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h4 class="font-display text-2xl text-white uppercase mb-2">{{ __('landing.about_val_3_title') }}</h4>
                <p class="text-light-grey text-sm leading-relaxed">{{ __('landing.about_val_3_desc') }}</p>
            </div>

            {{-- Value 4 --}}
            <div
                class="stagger-item card-solid p-8 rounded-2xl border border-border hover:border-secondary/40 transition-all duration-300">
                <div
                    class="w-12 h-12 rounded-xl bg-secondary/10 border border-secondary/20 flex items-center justify-center text-secondary mb-6">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h4 class="font-display text-2xl text-white uppercase mb-2">{{ __('landing.about_val_4_title') }}</h4>
                <p class="text-light-grey text-sm leading-relaxed">{{ __('landing.about_val_4_desc') }}</p>
            </div>
        </div>
    </div>

    {{-- 5. Milestones & Journey (Timeline) --}}
    <div class="container mx-auto px-5 py-24">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <p data-animate="fade-up" class="text-xs font-bold tracking-[0.25em] text-primary uppercase mb-2">
                {{ __('landing.about_timeline_label') }}
            </p>
            <h2 data-animate="fade-up" data-delay="0.1"
                class="font-display text-4xl sm:text-5xl text-white uppercase mb-4">
                {{ __('landing.about_timeline_title') }}
            </h2>
            <p data-animate="fade-up" data-delay="0.2" class="text-light-grey text-base">
                {{ __('landing.about_timeline_subtitle') }}
            </p>
        </div>

        <div data-animate="fade-up" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- Milestone 1 --}}
            <div class="card-solid p-6 rounded-2xl relative border-t-4 border-t-primary">
                <span
                    class="text-xs font-mono font-semibold text-primary block mb-2">{{ __('landing.about_time_1_period') }}</span>
                <h4 class="font-display text-2xl text-white uppercase mb-2">{{ __('landing.about_time_1_title') }}</h4>
                <p class="text-light-grey text-sm leading-relaxed">{{ __('landing.about_time_1_desc') }}</p>
            </div>

            {{-- Milestone 2 --}}
            <div class="card-solid p-6 rounded-2xl relative border-t-4 border-t-secondary">
                <span
                    class="text-xs font-mono font-semibold text-secondary block mb-2">{{ __('landing.about_time_2_period') }}</span>
                <h4 class="font-display text-2xl text-white uppercase mb-2">{{ __('landing.about_time_2_title') }}</h4>
                <p class="text-light-grey text-sm leading-relaxed">{{ __('landing.about_time_2_desc') }}</p>
            </div>

            {{-- Milestone 3 --}}
            <div class="card-solid p-6 rounded-2xl relative border-t-4 border-t-primary">
                <span
                    class="text-xs font-mono font-semibold text-primary block mb-2">{{ __('landing.about_time_3_period') }}</span>
                <h4 class="font-display text-2xl text-white uppercase mb-2">{{ __('landing.about_time_3_title') }}</h4>
                <p class="text-light-grey text-sm leading-relaxed">{{ __('landing.about_time_3_desc') }}</p>
            </div>

            {{-- Milestone 4 --}}
            <div class="card-solid p-6 rounded-2xl relative border-t-4 border-t-secondary">
                <span
                    class="text-xs font-mono font-semibold text-secondary block mb-2">{{ __('landing.about_time_4_period') }}</span>
                <h4 class="font-display text-2xl text-white uppercase mb-2">{{ __('landing.about_time_4_title') }}</h4>
                <p class="text-light-grey text-sm leading-relaxed">{{ __('landing.about_time_4_desc') }}</p>
            </div>
        </div>
    </div>

    {{-- 7. CTA Banner --}}
    <div class="container mx-auto px-5 py-24">
        <div data-animate="scale-in" class="card-solid p-10 sm:p-16 relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 opacity-5 pointer-events-none select-none">
                <span class="font-display text-[12rem] text-white leading-none">5</span>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center relative z-10">
                <div class="lg:col-span-7">
                    <h2 class="font-display text-3xl sm:text-4xl lg:text-5xl text-white leading-tight uppercase">
                        {{ __('landing.about_cta_heading') }}
                    </h2>
                </div>
                <div class="lg:col-span-5">
                    <p class="text-light-grey text-base leading-relaxed mb-8">
                        {{ __('landing.about_cta_body') }}
                    </p>
                    <x-btn-primary :href="url('/contact')" :label="__('landing.about_cta_btn')" />
                </div>
            </div>
        </div>
    </div>
@endsection
