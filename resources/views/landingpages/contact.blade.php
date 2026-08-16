@extends('layouts.landingpages')

@php
    $locale = app()->getLocale();
@endphp

@push('title', ($locale === 'id' ? 'Hubungi Kami' : 'Contact Us') . ' — Lima Biji Agritech')

@push('meta')
    <meta name="description" content="{{ __('landing.contact_hero_subheading') }}">
    <meta property="og:title" content="{{ ($locale === 'id' ? 'Hubungi Kami' : 'Contact Us') . ' — Lima Biji Agritech' }}">
    <meta property="og:description" content="{{ __('landing.contact_hero_subheading') }}">
    <meta property="og:type" content="website">
    <link rel="canonical" href="{{ url()->current() }}">
@endpush

@section('content')
{{-- 1. Hero Section --}}
<div class="container mx-auto px-5 pt-10 pb-10 lg:pt-5 lg:pb-5">
    <div class="max-w-5xl">
        <p data-animate="fade-up" class="text-sm lg:text-2xl font-bold tracking-[0.3em] uppercase text-light-grey mb-6">
            {{ __('landing.contact_hero_label') }}
        </p>
        <h1 data-animate="fade-up" data-delay="0.1" class="font-display text-8xl sm:text-9xl lg:text-[12rem] text-white leading-[0.85] uppercase">
            {!! __('landing.contact_hero_heading') !!}
        </h1>
        <p data-animate="fade-up" data-delay="0.2" class="text-light-grey text-lg mt-8 max-w-xl">
            {{ __('landing.contact_hero_subheading') }}
        </p>
    </div>
</div>

{{-- 2. Contact Details & Form Section --}}
<div data-animate="fade-up" class="container mx-auto px-5 py-24">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
        {{-- Contact Info Column --}}
        <div class="lg:col-span-5 space-y-8">
            <div>
                <h2 class="section-heading">{{ __('landing.contact_title') }}</h2>
                <p class="section-subtitle mt-4">{{ __('landing.contact_subtitle') }}</p>
            </div>

            <div class="space-y-6 pt-4">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-primary/10 border border-primary/20 flex items-center justify-center text-primary flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-white font-bold text-base mb-1">{{ __('landing.contact_email_label') }}</h4>
                        <a href="mailto:export@limabijiagritech.com" class="text-light-grey hover:text-primary transition-colors text-sm font-medium">export@limabijiagritech.com</a>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-secondary/10 border border-secondary/20 flex items-center justify-center text-secondary flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-white font-bold text-base mb-1">{{ __('landing.contact_phone_label') }}</h4>
                        <a href="tel:+6281234567890" class="text-light-grey hover:text-primary transition-colors text-sm font-medium">+62 812 3456 7890</a>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-primary/10 border border-primary/20 flex items-center justify-center text-primary flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-white font-bold text-base mb-1">{{ __('landing.contact_office_label') }}</h4>
                        <p class="text-light-grey text-sm leading-relaxed">{{ __('landing.contact_address') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Contact Form Column --}}
        <div class="lg:col-span-7">
            <div class="card-solid p-8 sm:p-10">
                <form action="#" method="POST" onsubmit="event.preventDefault(); alert('{{ __('landing.contact_form_alert') }}');" class="space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-white font-semibold text-xs uppercase tracking-wider mb-2">{{ __('landing.contact_form_name') }} *</label>
                            <input type="text" required class="w-full bg-surface-alt border border-border rounded-lg p-3 text-white placeholder:text-light-grey/50 text-sm focus:border-primary focus:outline-none transition-colors" placeholder="John Doe">
                        </div>
                        <div>
                            <label class="block text-white font-semibold text-xs uppercase tracking-wider mb-2">{{ __('landing.contact_form_email') }} *</label>
                            <input type="email" required class="w-full bg-surface-alt border border-border rounded-lg p-3 text-white placeholder:text-light-grey/50 text-sm focus:border-primary focus:outline-none transition-colors" placeholder="john@example.com">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-white font-semibold text-xs uppercase tracking-wider mb-2">{{ __('landing.contact_form_company') }}</label>
                            <input type="text" class="w-full bg-surface-alt border border-border rounded-lg p-3 text-white placeholder:text-light-grey/50 text-sm focus:border-primary focus:outline-none transition-colors" placeholder="Specialty Roastery Co.">
                        </div>
                        <div>
                            <label class="block text-white font-semibold text-xs uppercase tracking-wider mb-2">{{ __('landing.contact_form_subject') }}</label>
                            <select class="w-full bg-surface-alt border border-border rounded-lg p-3 text-white text-sm focus:border-primary focus:outline-none transition-colors">
                                <option class="bg-surface-alt text-white">{{ __('landing.contact_form_subj_sample') }}</option>
                                <option class="bg-surface-alt text-white">{{ __('landing.contact_form_subj_bulk') }}</option>
                                <option class="bg-surface-alt text-white">{{ __('landing.contact_form_subj_partnership') }}</option>
                                <option class="bg-surface-alt text-white">{{ __('landing.contact_form_subj_general') }}</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-white font-semibold text-xs uppercase tracking-wider mb-2">{{ __('landing.contact_form_message') }} *</label>
                        <textarea required rows="4" class="w-full bg-surface-alt border border-border rounded-lg p-3 text-white placeholder:text-light-grey/50 text-sm focus:border-primary focus:outline-none transition-colors" placeholder="{{ __('landing.contact_form_message_placeholder') }}"></textarea>
                    </div>

                    <button type="submit" class="inline-flex items-center justify-center gap-2 bg-primary hover:bg-primary-hover text-white px-8 py-3 rounded-lg font-medium text-base transition-all duration-300 shadow-md hover:shadow-lg w-full sm:w-auto">
                        <span>{{ __('landing.contact_form_submit') }}</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
