@extends('layouts.landingpages')

@php($locale = app()->getLocale())
@php($heroBlock = $page?->blocks->firstWhere('block_type', 'hero'))
@php($hero = $heroBlock?->getContent($locale) ?: [])
@php($heroLabel = $hero['label'] ?? '')
@php($heroHeading = $hero['heading'] ?? '')
@php($heroSubheading = $hero['subheading'] ?? '')

@push('title', 'Client Testimonials — Lima Biji Agritech')

@push('meta')
    <meta name="description" content="{{ strip_tags($heroSubheading) }}">
    <meta property="og:title" content="Client Testimonials — Lima Biji Agritech">
    <meta property="og:description" content="{{ strip_tags($heroSubheading) }}">
    <meta property="og:type" content="website">
    <link rel="canonical" href="{{ url()->current() }}">
@endpush

@section('content')
    <div class="container mx-auto px-5 pt-10 pb-10 lg:pt-5 lg:pb-5">
        <div class="max-w-3xl">
            @if($heroLabel)
                <p data-animate="fade-up" class="text-sm font-bold tracking-[0.3em] uppercase text-light-grey mb-6">{{ $heroLabel }}</p>
            @endif
            @if($heroHeading)
                <h1 data-animate="fade-up" data-delay="0.1" class="font-display text-7xl sm:text-9xl lg:text-[10rem] text-dark leading-[0.85] uppercase">
                    {!! $heroHeading !!}
                </h1>
            @endif
            @if($heroSubheading)
                <p data-animate="fade-up" data-delay="0.2" class="text-light-grey text-lg mt-6 max-w-xl">
                    {!! $heroSubheading !!}
                </p>
            @endif
        </div>
    </div>

    <div class="container mx-auto px-5 pb-24">
        <div data-animate="stagger" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($testimonials as $testimonial)
                <div class="stagger-item">
                    <x-testimonial-card :testimonial="$testimonial" />
                </div>
            @endforeach
        </div>

        @if (count($testimonials) === 0)
            <div class="text-center py-20">
                <p class="text-light-grey text-lg">No client testimonials recorded yet.</p>
            </div>
        @endif
    </div>
@endsection
