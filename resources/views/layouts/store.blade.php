{{-- resources/views/layouts/store.blade.php --}}
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>@stack('title')</title>
    @stack('meta')
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta property="og:site_name" content="Lima Biji Agritech Store">
    <meta property="og:locale" content="{{ app()->getLocale() === 'id' ? 'id_ID' : 'en_US' }}">
    <meta name="twitter:card" content="summary_large_image">

    @stack('styles')

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Rubik:wght@300..700&display=swap"
        rel="stylesheet">
    @stack('modules')

    {{-- Structured Data Schema --}}
    @stack('schema')
</head>

<body class="bg-[#fafaf9] text-slate-800 antialiased font-sans flex flex-col min-h-screen">
    {{-- Dedicated Store Header Navbar --}}
    <x-store.nav-bar />

    {{-- Main Store View Body --}}
    <main class="flex-1 bg-[#fafaf9]">
        @yield('content')
    </main>

    {{-- Dedicated Store Footer --}}
    <x-store.footer />

    {{-- Store Modals (Cart Slide-over & Coffee Finder Quiz) --}}
    @include('components.store.cart-drawer')
    @include('components.store.quiz-modal')

    @stack('scripts')
</body>

</html>
