@extends('layouts.landingpages')

@php
    $locale = app()->getLocale();
    $contactEmail = \App\Models\SiteSetting::get('contact_email', null, 'export@limabijiagritech.com');
    $contactPhone = \App\Models\SiteSetting::get('contact_phone', null, '+62 812 3456 7890');
    $contactHours = \App\Models\SiteSetting::get('contact_hours', null, 'Mon – Fri, 8:00 – 16:00 WIB');
    $contactAddress = \App\Models\SiteSetting::get('contact_address', $locale, __('landing.contact_address'));
    $phoneSanitized = preg_replace('/[^0-9+]/', '', $contactPhone);

    // Map Settings
    $mapLat = (float) \App\Models\SiteSetting::get('map_lat', null, -6.5971);
    $mapLng = (float) \App\Models\SiteSetting::get('map_lng', null, 106.806);
    $mapZoom = (int) \App\Models\SiteSetting::get('map_zoom', null, 14);
    $mapLabel = \App\Models\SiteSetting::get('map_label', null, 'Lima Biji Agritech');
    $customEmbedUrl = \App\Models\SiteSetting::get('map_embed_url', null, '');
    $mapEmbedUrl = !empty($customEmbedUrl)
        ? $customEmbedUrl
        : "https://maps.google.com/maps?q={$mapLat},{$mapLng}&hl={$locale}&z={$mapZoom}&output=embed";
@endphp

@push('title', ($locale === 'id' ? 'Hubungi Kami' : 'Contact Us') . ' — Lima Biji Agritech')

@push('meta')
    <meta name="description" content="{{ __('landing.contact_hero_subheading') }}">
    <meta property="og:title" content="{{ ($locale === 'id' ? 'Hubungi Kami' : 'Contact Us') . ' — Lima Biji Agritech' }}">
    <meta property="og:description" content="{{ __('landing.contact_hero_subheading') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('favicon.ico') }}">
    <meta name="twitter:title" content="{{ ($locale === 'id' ? 'Hubungi Kami' : 'Contact Us') . ' — Lima Biji Agritech' }}">
    <meta name="twitter:description" content="{{ __('landing.contact_hero_subheading') }}">
    <meta name="twitter:image" content="{{ asset('favicon.ico') }}">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Preconnect Google Maps hints for fast iframe loading --}}
    <link rel="preconnect" href="https://maps.google.com" crossorigin>
    <link rel="dns-prefetch" href="https://maps.google.com">
@endpush

@push('schema')
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => 'ContactPage',
                    '@id' => url()->current() . '#webpage',
                    'url' => url()->current(),
                    'name' => ($locale === 'id' ? 'Hubungi Kami' : 'Contact Us') . ' — Lima Biji Agritech',
                    'description' => __('landing.contact_hero_subheading'),
                    'inLanguage' => $locale,
                ],
                [
                    '@type' => 'LocalBusiness',
                    '@id' => url('/') . '#business',
                    'name' => 'Lima Biji Agritech',
                    'url' => url('/'),
                    'telephone' => $contactPhone,
                    'email' => $contactEmail,
                    'address' => [
                        '@type' => 'PostalAddress',
                        'streetAddress' => $contactAddress,
                        'addressCountry' => 'ID',
                    ],
                    'geo' => [
                        '@type' => 'GeoCoordinates',
                        'latitude' => $mapLat,
                        'longitude' => $mapLng,
                    ],
                    'openingHours' => 'Mo-Fr 08:00-16:00',
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
                            'name' => $locale === 'id' ? 'Kontak' : 'Contact',
                            'item' => url()->current(),
                        ],
                    ],
                ],
            ],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endpush

@section('content')
    <div class="container mx-auto px-5 pt-10 pb-10 lg:pt-5 lg:pb-5">
        <div class="max-w-5xl">
            <p data-animate="fade-up" class="text-sm lg:text-2xl font-bold tracking-[0.3em] uppercase text-light-grey mb-6">
                {{ __('landing.contact_hero_label') }}
            </p>
            <h1 data-animate="fade-up" data-delay="0.1"
                class="font-display text-8xl sm:text-9xl lg:text-[12rem] text-white leading-[0.85] uppercase">
                {!! __('landing.contact_hero_heading') !!}
            </h1>
            <p data-animate="fade-up" data-delay="0.2" class="text-light-grey text-lg mt-8 max-w-xl">
                {{ __('landing.contact_hero_subheading') }}
            </p>
        </div>
    </div>

    <div data-animate="fade-up" class="container mx-auto px-5 py-16 lg:py-24">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
            {{-- Contact Info Column --}}
            <div class="lg:col-span-5 space-y-8">
                <div>
                    <h2 class="section-heading">{{ __('landing.contact_title') }}</h2>
                    <p class="section-subtitle mt-4">{{ __('landing.contact_subtitle') }}</p>
                </div>

                <div class="space-y-6 pt-4">
                    <div class="flex items-start gap-4">
                        <div
                            class="w-12 h-12 rounded-xl bg-primary/10 border border-primary/20 flex items-center justify-center text-primary flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-white font-bold text-base mb-1">{{ __('landing.contact_email_label') }}</h4>
                            <a href="mailto:{{ $contactEmail }}"
                                class="text-light-grey hover:text-primary transition-colors text-sm font-medium">{{ $contactEmail }}</a>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div
                            class="w-12 h-12 rounded-xl bg-secondary/10 border border-secondary/20 flex items-center justify-center text-secondary flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-white font-bold text-base mb-1">{{ __('landing.contact_phone_label') }}</h4>
                            <a href="tel:{{ $phoneSanitized }}"
                                class="text-light-grey hover:text-primary transition-colors text-sm font-medium">{{ $contactPhone }}</a>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div
                            class="w-12 h-12 rounded-xl bg-primary/10 border border-primary/20 flex items-center justify-center text-primary flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-white font-bold text-base mb-1">{{ __('landing.contact_office_label') }}</h4>
                            <p class="text-light-grey text-sm leading-relaxed">{{ $contactAddress }}</p>
                        </div>
                    </div>

                    @if ($contactHours)
                        <div class="flex items-start gap-4">
                            <div
                                class="w-12 h-12 rounded-xl bg-secondary/10 border border-secondary/20 flex items-center justify-center text-secondary flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-white font-bold text-base mb-1">{{ __('landing.contact_hours_label') }}
                                </h4>
                                <p class="text-light-grey text-sm leading-relaxed">{{ $contactHours }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Contact Form Column --}}
            <div class="lg:col-span-7">
                <div class="card-solid p-8 sm:p-10">
                    @if (session('success'))
                        <div
                            class="mb-8 p-4 rounded-xl bg-primary/20 border border-primary/40 text-white flex items-start gap-3.5 shadow-lg">
                            <div
                                class="w-7 h-7 rounded-full bg-primary/30 flex items-center justify-center text-primary flex-shrink-0 mt-0.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm text-primary">
                                    {{ $locale === 'id' ? 'Terkirim!' : 'Message Sent!' }}</h4>
                                <p class="text-xs text-white/90 mt-0.5">{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('landingpages.contact.submit') }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label
                                    class="block text-white font-semibold text-xs uppercase tracking-wider mb-2">{{ __('landing.contact_form_name') }}
                                    *</label>
                                <input type="text" name="name" value="{{ old('name') }}" required
                                    class="w-full bg-surface-alt border @error('name') border-rose-500 @else border-border @enderror rounded-lg p-3 text-white placeholder:text-light-grey/50 text-sm focus:border-primary focus:outline-none transition-colors"
                                    placeholder="John Doe">
                                @error('name')
                                    <p class="text-rose-400 text-xs mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label
                                    class="block text-white font-semibold text-xs uppercase tracking-wider mb-2">{{ __('landing.contact_form_email') }}
                                    *</label>
                                <input type="email" name="email" value="{{ old('email') }}" required
                                    class="w-full bg-surface-alt border @error('email') border-rose-500 @else border-border @enderror rounded-lg p-3 text-white placeholder:text-light-grey/50 text-sm focus:border-primary focus:outline-none transition-colors"
                                    placeholder="john@example.com">
                                @error('email')
                                    <p class="text-rose-400 text-xs mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label
                                    class="block text-white font-semibold text-xs uppercase tracking-wider mb-2">{{ __('landing.contact_form_company') }}</label>
                                <input type="text" name="company" value="{{ old('company') }}"
                                    class="w-full bg-surface-alt border @error('company') border-rose-500 @else border-border @enderror rounded-lg p-3 text-white placeholder:text-light-grey/50 text-sm focus:border-primary focus:outline-none transition-colors"
                                    placeholder="Specialty Roastery Co.">
                                @error('company')
                                    <p class="text-rose-400 text-xs mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label
                                    class="block text-white font-semibold text-xs uppercase tracking-wider mb-2">{{ __('landing.contact_form_subject') }}</label>
                                <select name="subject"
                                    class="w-full bg-surface-alt border @error('subject') border-rose-500 @else border-border @enderror rounded-lg p-3 text-white text-sm focus:border-primary focus:outline-none transition-colors">
                                    <option value="{{ __('landing.contact_form_subj_sample') }}"
                                        {{ old('subject') === __('landing.contact_form_subj_sample') ? 'selected' : '' }}
                                        class="bg-surface-alt text-white">{{ __('landing.contact_form_subj_sample') }}
                                    </option>
                                    <option value="{{ __('landing.contact_form_subj_bulk') }}"
                                        {{ old('subject') === __('landing.contact_form_subj_bulk') ? 'selected' : '' }}
                                        class="bg-surface-alt text-white">{{ __('landing.contact_form_subj_bulk') }}
                                    </option>
                                    <option value="{{ __('landing.contact_form_subj_partnership') }}"
                                        {{ old('subject') === __('landing.contact_form_subj_partnership') ? 'selected' : '' }}
                                        class="bg-surface-alt text-white">
                                        {{ __('landing.contact_form_subj_partnership') }}</option>
                                    <option value="{{ __('landing.contact_form_subj_general') }}"
                                        {{ old('subject') === __('landing.contact_form_subj_general') ? 'selected' : '' }}
                                        class="bg-surface-alt text-white">{{ __('landing.contact_form_subj_general') }}
                                    </option>
                                </select>
                                @error('subject')
                                    <p class="text-rose-400 text-xs mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-white font-semibold text-xs uppercase tracking-wider mb-2">{{ __('landing.contact_form_message') }}
                                *</label>
                            <textarea name="message" required rows="4"
                                class="w-full bg-surface-alt border @error('message') border-rose-500 @else border-border @enderror rounded-lg p-3 text-white placeholder:text-light-grey/50 text-sm focus:border-primary focus:outline-none transition-colors"
                                placeholder="{{ __('landing.contact_form_message_placeholder') }}">{{ old('message') }}</textarea>
                            @error('message')
                                <p class="text-rose-400 text-xs mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit"
                            class="inline-flex items-center justify-center gap-2 bg-primary hover:bg-primary-hover text-white px-8 py-3 rounded-lg font-medium text-base transition-all duration-300 shadow-md hover:shadow-lg w-full sm:w-auto">
                            <span>{{ __('landing.contact_form_submit') }}</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. Store Location & Interactive Map Section --}}
    <div data-animate="fade-up" class="container mx-auto px-5 pb-24">
        <div class=" p-6 sm:p-10 relative overflow-hidden">
            {{-- Section Header --}}
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
                <div>
                    <p class="text-xs sm:text-sm font-bold tracking-[0.25em] uppercase text-primary mb-2">
                        {{ $locale === 'id' ? 'LOKASI KAMI' : 'STORE & OFFICE LOCATION' }}
                    </p>
                    <h3 class="font-display text-3xl sm:text-4xl lg:text-5xl text-white uppercase leading-none">
                        {{ $locale === 'id' ? 'Kunjungi Kantor & Roastery Kami' : 'Visit Our Headquarters' }}
                    </h3>
                </div>
                <div class="flex items-center gap-3">
                    <a href="https://www.google.com/maps/search/?api=1&query={{ $mapLat }},{{ $mapLng }}"
                        target="_blank" rel="noopener noreferrer"
                        class="inline-flex items-center gap-2 bg-surface-alt hover:bg-primary/20 border border-border hover:border-primary/40 text-white hover:text-primary px-5 py-2.5 rounded-xl text-xs font-semibold tracking-wide transition-all duration-300 shadow-sm">
                        <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                        <span>{{ $locale === 'id' ? 'Buka di Google Maps' : 'Open in Google Maps' }}</span>
                    </a>
                </div>
            </div>

            {{-- Map Iframe Container --}}
            <div class="relative w-full h-[460px] rounded-2xl overflow-hidden border border-border/80 bg-surface-alt shadow-inner">
                <iframe
                    id="contact-map-iframe"
                    src="{{ $mapEmbedUrl }}"
                    width="100%"
                    height="100%"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    title="{{ $mapLabel }}">
                </iframe>
            </div>
        </div>
    </div>
@endsection
