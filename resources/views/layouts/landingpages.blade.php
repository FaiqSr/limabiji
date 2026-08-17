<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>@stack('title')</title>
    @stack('meta')
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    {{-- SEO hreflang --}}
    <link rel="alternate" hreflang="en" href="{{ url('/en') }}">
    <link rel="alternate" hreflang="id" href="{{ url('/id') }}">
    <link rel="alternate" hreflang="x-default" href="{{ url('/') }}">

    @stack('styles')

    {{-- Font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Rubik:wght@300..700&display=swap"
        rel="stylesheet">
    @stack('modules')



    {{-- Animation CSS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    {{-- Motion Js --}}
    <script src="https://cdn.jsdelivr.net/npm/motion@latest/dist/motion.js"></script>

</head>

<body>
    <x-nav-bar />
    <section class="bg-surface min-h-screen">
        @yield('content')
        <x-footer />
    </section>

    {{-- Motion.js Shared Animation Engine --}}
    <script>
        (function() {
            const {
                animate,
                inView,
                stagger
            } = Motion;

            // Fade up + slide
            inView("[data-animate='fade-up']", (element) => {
                animate(element, {
                    opacity: [0, 1],
                    y: [48, 0]
                }, {
                    duration: 0.7,
                    easing: [0.25, 0.46, 0.45, 0.94],
                    delay: element.dataset.delay ? parseFloat(element.dataset.delay) : 0
                });
                return () => animate(element, {
                    opacity: 0,
                    y: 48
                }, {
                    duration: 0.3
                });
            });

            // Fade in only
            inView("[data-animate='fade-in']", (element) => {
                animate(element, {
                    opacity: [0, 1]
                }, {
                    duration: 0.8,
                    easing: "ease-out",
                    delay: element.dataset.delay ? parseFloat(element.dataset.delay) : 0
                });
                return () => animate(element, {
                    opacity: 0
                }, {
                    duration: 0.3
                });
            });

            // Stagger children — each child with .stagger-item fades up sequentially
            inView("[data-animate='stagger']", (element) => {
                const items = element.querySelectorAll('.stagger-item');
                animate(items, {
                    opacity: [0, 1],
                    y: [32, 0]
                }, {
                    duration: 0.5,
                    easing: "ease-out",
                    delay: stagger(0.1)
                });
                return () => animate(items, {
                    opacity: 0,
                    y: 32
                }, {
                    duration: 0.2,
                    delay: stagger(0)
                });
            });

            // Scale in
            inView("[data-animate='scale-in']", (element) => {
                animate(element, {
                    opacity: [0, 1],
                    scale: [0.92, 1]
                }, {
                    duration: 0.6,
                    easing: [0.25, 0.46, 0.45, 0.94],
                    delay: element.dataset.delay ? parseFloat(element.dataset.delay) : 0
                });
                return () => animate(element, {
                    opacity: 0,
                    scale: 0.92
                }, {
                    duration: 0.2
                });
            });
        })();
    </script>

    @stack('scripts')
</body>

</html>
