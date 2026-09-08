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
    <meta property="og:site_name" content="Lima Biji Agritech">
    <meta property="og:locale" content="{{ app()->getLocale() === 'id' ? 'id_ID' : 'en_US' }}">
    <meta name="twitter:card" content="summary_large_image">

    @stack('styles')

    {{-- Font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Rubik:wght@300..700&display=swap"
        rel="stylesheet">
    @stack('modules')

    {{-- Structured Data Schema --}}
    @stack('schema')


    <style>
        /* === Overlay === */
        #sidebar-overlay {
            position: fixed;
            inset: 0;
            z-index: 60;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.35s ease;
        }

        #sidebar-overlay.active {
            opacity: 1;
            pointer-events: auto;
        }

        /* === Sidebar === */
        #sidebar {
            position: fixed;
            top: 0;
            right: 0;
            z-index: 70;
            width: 320px;
            max-width: 85vw;
            height: 100%;
            overflow-y: auto;
            background: #142622;
            border-left: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            flex-direction: column;
            transform: translateX(100%);
            transition: transform 0.35s ease-out;
        }

        #sidebar.active {
            transform: translateX(0);
        }

        /* === Sidebar Header === */
        .sidebar-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 24px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        #sidebar-close {
            color: rgba(255, 255, 255, 0.6);
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px;
            transition: color 0.2s;
        }

        #sidebar-close:hover {
            color: #FFFFFF;
        }

        /* === Sidebar Nav === */
        .sidebar-nav {
            display: flex;
            flex-direction: column;
            padding: 24px;
            gap: 4px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 16px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .sidebar-link:hover {
            background: rgba(255, 255, 255, 0.08);
            color: #FFFFFF;
        }

        .sidebar-link .arrow {
            opacity: 0;
            transform: translateX(-4px);
            transition: all 0.2s ease;
        }

        .sidebar-link:hover .arrow {
            opacity: 1;
            transform: translateX(0);
        }

        /* === Sidebar Footer === */
        .sidebar-footer {
            margin-top: auto;
            padding: 24px;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }

        .sidebar-footer p:first-child {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.25);
        }

        .sidebar-footer p:last-child {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.15);
            margin-top: 4px;
        }
    </style>


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

    <script>
        (function() {
            var openBtn = document.getElementById('sidebar-open');
            var closeBtn = document.getElementById('sidebar-close');
            var overlay = document.getElementById('sidebar-overlay');
            var sidebar = document.getElementById('sidebar');
            var links = sidebar.querySelectorAll('.sidebar-link');
            var active = false;

            function open() {
                active = true;
                overlay.classList.add('active');
                sidebar.classList.add('active');
                document.body.style.overflow = 'hidden';
            }

            function close() {
                active = false;
                overlay.classList.remove('active');
                sidebar.classList.remove('active');
                document.body.style.overflow = '';
            }

            if (openBtn) openBtn.addEventListener('click', open);
            if (closeBtn) closeBtn.addEventListener('click', close);
            if (overlay) overlay.addEventListener('click', close);

            links.forEach(function(link) {
                link.addEventListener('click', function() {
                    setTimeout(close, 150);
                });
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && active) close();
            });
        })();
    </script>

    {{-- Store Components (Cart Drawer & Coffee Finder Quiz) --}}
    @include('components.store.cart-drawer')
    @include('components.store.quiz-modal')

    @stack('scripts')
</body>

</html>
