@extends('layouts.landingpages')

@php
    $locale = app()->getLocale();
    $pageTitle = ($locale === 'id' ? 'Kebijakan Privasi' : 'Privacy Policy') . ' — Lima Biji Agritech';
    $privacyEmail = \App\Models\SiteSetting::get('contact_email', null, 'export@limabijiagritech.com');
@endphp

@push('title', $pageTitle)

@push('meta')
    <meta name="description" content="{{ __('landing.privacy_meta') }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ __('landing.privacy_meta') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('favicon.ico') }}">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ __('landing.privacy_meta') }}">
    <link rel="canonical" href="{{ url()->current() }}">
@endpush

@section('content')
    {{-- Hero --}}
    <div class="container mx-auto px-5 pt-16 pb-12 lg:pt-20">
        <div class="max-w-4xl">
            <p data-animate="fade-up" class="text-xs font-bold tracking-[0.3em] uppercase text-primary mb-4">
                {{ __('landing.privacy_label') }}
            </p>
            <h1 data-animate="fade-up" data-delay="0.1"
                class="font-display text-6xl sm:text-8xl text-white leading-[0.9] uppercase">
                {{ __('landing.privacy_heading') }}
            </h1>
            <p data-animate="fade-up" data-delay="0.2" class="text-light-grey text-sm font-mono mt-6">
                {{ __('landing.privacy_last_updated') }}
            </p>
        </div>
    </div>

    {{-- Policy body --}}
    <div data-animate="fade-up" class="container mx-auto flex justify-center px-5 pb-24">
        <div class="rounded-2xl p-8 sm:p-12 lg:p-16 max-w-7xl">
            <div class="article-body">
                @foreach (__('landing.privacy_sections') as $section)
                    <h2>{{ $section['heading'] }}</h2>
                    @if (! empty($section['body']))
                        <p>{{ $section['body'] }}</p>
                    @endif
                    @if (! empty($section['items']))
                        <ul>
                            @foreach ($section['items'] as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                    @endif
                @endforeach

                <h2>{{ __('landing.privacy_contact_heading') }}</h2>
                <p>
                    {{ __('landing.privacy_contact_body') }}
                    <a href="mailto:{{ $privacyEmail }}" class="text-primary underline hover:text-white transition-colors">{{ $privacyEmail }}</a>
                </p>
            </div>
        </div>
    </div>
@endsection
