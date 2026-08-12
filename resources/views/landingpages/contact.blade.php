@extends('layouts.landingpages')

@php($locale = app()->getLocale())

@php($heroBlock = $page?->blocks->firstWhere('block_type', 'hero'))
@php($hero = $heroBlock?->getContent($locale) ?: [])
@php($heroLabel = $hero['label'] ?? '')
@php($heroHeading = $hero['heading'] ?? '')
@php($heroSubheading = $hero['subheading'] ?? '')

@php($address = isset($settings['contact_address']) ? (is_array($settings['contact_address']->value) ? json_encode($settings['contact_address']->value) : $settings['contact_address']->value) : '')
@php($email = isset($settings['contact_email']) ? (is_array($settings['contact_email']->value) ? json_encode($settings['contact_email']->value) : $settings['contact_email']->value) : '')
@php($hours = isset($settings['contact_hours']) ? (is_array($settings['contact_hours']->value) ? json_encode($settings['contact_hours']->value) : $settings['contact_hours']->value) : '')

@push('title', 'Contact Export Office — Lima Biji Agritech')

@php($metaSub = strip_tags($heroSubheading))
@php($metaSub = strlen($metaSub) > 160 ? substr($metaSub, 0, 160) : $metaSub)

@push('meta')
    <meta name="description" content="{{ $metaSub }}">
    <meta property="og:title" content="Contact Export Office — Lima Biji Agritech">
    <meta property="og:description" content="{{ $metaSub }}">
    <meta property="og:type" content="website">
    <link rel="canonical" href="{{ url()->current() }}">
@endpush

@section('content')
    {{-- Hero --}}
    <div class="container mx-auto px-5 pt-10 pb-10 lg:pt-5 lg:pb-5">
        <div class="max-w-3xl">
            <p data-animate="fade-up" class="text-sm font-bold tracking-[0.3em] uppercase text-light-grey mb-6">{{ $heroLabel }}</p>
            <h1 data-animate="fade-up" data-delay="0.1" class="font-display text-7xl sm:text-9xl lg:text-[10rem] text-dark leading-[0.85] uppercase">
                {!! $heroHeading !!}
            </h1>
            <p data-animate="fade-up" data-delay="0.2" class="text-light-grey text-lg mt-6 max-w-xl">
                {!! $heroSubheading !!}
            </p>
        </div>
    </div>

    {{-- Contact Info + Form --}}
    <div data-animate="fade-up" class="container mx-auto px-5 py-24">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
            {{-- Info --}}
            <div>
                <h2 class="font-display text-3xl text-dark uppercase mb-6">Export Office</h2>
                <div class="space-y-6 text-light-grey">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-dark/40 mb-1">Address</p>
                        <p class="text-dark/70">{{ $address }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-dark/40 mb-1">Email</p>
                        <a href="mailto:{{ $email }}" class="text-dark hover:text-primary transition-colors">{{ $email }}</a>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-dark/40 mb-1">Hours</p>
                        <p class="text-dark/70">{{ $hours }}</p>
                    </div>
                </div>
            </div>

            {{-- Form --}}
            <div data-animate="scale-in" class="card-solid p-8 sm:p-10">
                <h3 class="font-display text-2xl text-dark uppercase mb-8">Send a Message</h3>
                <form class="space-y-6">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-dark/40 mb-2">Name</label>
                        <input type="text" class="w-full bg-surface-alt border border-border rounded-lg px-4 py-3 text-dark placeholder-light-grey/30 focus:outline-none focus:border-primary/50 transition-colors" placeholder="Your name">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-dark/40 mb-2">Email</label>
                        <input type="email" class="w-full bg-surface-alt border border-border rounded-lg px-4 py-3 text-dark placeholder-light-grey/30 focus:outline-none focus:border-primary/50 transition-colors" placeholder="you@roastery.com">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-dark/40 mb-2">Message</label>
                        <textarea rows="5" class="w-full bg-surface-alt border border-border rounded-lg px-4 py-3 text-dark placeholder-light-grey/30 focus:outline-none focus:border-primary/50 transition-colors resize-none" placeholder="Tell us about your requirements..."></textarea>
                    </div>
                    <x-btn-primary href="#" label="Send Message" />
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => 'Lima Biji Agritech',
            'url' => url('/'),
            'email' => $email,
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => $address,
            ],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endpush
