@extends('layouts.landingpages')

@php
    $locale = app()->getLocale();
@endphp

@push('title', ($locale === 'id' ? 'Testimoni' : 'Testimonials') . ' — Lima Biji Agritech')

@push('meta')
    <meta name="description" content="{{ __('landing.testimonials_subheading') }}">
    <meta property="og:title" content="{{ ($locale === 'id' ? 'Testimoni' : 'Testimonials') . ' — Lima Biji Agritech' }}">
    <meta property="og:description" content="{{ __('landing.testimonials_subheading') }}">
    <meta property="og:type" content="website">
    <link rel="canonical" href="{{ url()->current() }}">
@endpush

@section('content')
{{-- 1. Hero Section --}}
<div class="container mx-auto px-5 pt-10 pb-10 lg:pt-5 lg:pb-5">
    <div class="max-w-5xl">
        <p data-animate="fade-up" class="text-sm lg:text-2xl font-bold tracking-[0.3em] uppercase text-light-grey mb-6">
            {{ $locale === 'id' ? 'Testimoni' : 'Testimonials' }}
        </p>
        <h1 data-animate="fade-up" data-delay="0.1" class="font-display text-8xl sm:text-9xl lg:text-[12rem] text-white leading-[0.85] uppercase">
            {!! __('landing.testimonials_heading') !!}
        </h1>
        <p data-animate="fade-up" data-delay="0.2" class="text-light-grey text-lg mt-8 max-w-xl">
            {{ __('landing.testimonials_subheading') }}
        </p>
    </div>
</div>

{{-- 2. Testimonials Grid Section --}}
@if($testimonials->isNotEmpty())
<div data-animate="fade-up" class="container mx-auto px-5 py-24">
    <div data-animate="stagger" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($testimonials as $testimonial)
            <div class="stagger-item">
                <x-testimonial-card :testimonial="$testimonial" />
            </div>
        @endforeach
    </div>

    @if($testimonials->hasPages())
        <div class="mt-12 flex justify-center">
            {{ $testimonials->links() }}
        </div>
    @endif
</div>
@endif
@endsection
