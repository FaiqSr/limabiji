<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>@stack('title')</title>

    @stack('styles')

    {{-- Font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alfa+Slab+One&family=Squada+One&family=Suez+One&display=swap"
        rel="stylesheet">

    @stack('modules')

    {{-- Animation CSS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    {{-- Motion Js --}}
    <script src="https://cdn.jsdelivr.net/npm/motion@latest/dist/motion.js"></script>

</head>

<body>
    <x-nav-bar />
    <section class="bg-linear-to-b from-dark to-secondary min-h-screen">
        @yield('content')
    </section>
    @stack('scripts')
</body>

</html>
