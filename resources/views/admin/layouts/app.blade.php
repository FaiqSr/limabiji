<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — Lima Biji Admin</title>
    <meta name="robots" content="noindex, nofollow">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:400,500,600,700|jetbrains-mono:400" rel="stylesheet" />

    <!-- Alpine.js Plugins -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <!-- Alpine.js Core -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.9/dist/cdn.min.js"></script>

    <!-- Trix Rich Text Editor -->
    <link rel="stylesheet" href="https://unpkg.com/trix@2.1.13/dist/trix.css">
    <style>
        trix-toolbar { border-radius: 8px 8px 0 0; border-color: #e2e8f0; }
        trix-toolbar .trix-button-group { border-color: #e2e8f0; }
        trix-toolbar .trix-button { border-color: transparent; }
        trix-editor {
            border-radius: 0 0 8px 8px;
            border-color: #e2e8f0;
            min-height: 120px;
            font-size: 0.8125rem;
            line-height: 1.5;
            padding: 0.75rem;
        }
        trix-editor:focus { border-color: #079f81; outline: none; }
        trix-toolbar .trix-button-group--file-tools { display: none; }
        trix-toolbar .trix-button--icon-code { display: none; }
        trix-toolbar .trix-button--icon-quote { display: none; }
    </style>

    @vite(['resources/css/admin.css', 'resources/js/app.js'])

    @stack('print-styles')
</head>
<body class="font-body bg-slate-50 text-slate-900 antialiased selection:bg-emerald-500 selection:text-white" x-data="{ mobileOpen: false }">

    <div class="flex min-h-[100dvh]">
        <!-- Sidebar -->
        <x-admin.sidebar />

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col min-w-0 lg:pl-64">
            <!-- Header -->
            <x-admin.header />

            <!-- Page Content Body -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-7xl w-full mx-auto">
                @if (session('success'))
                    <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50/80 p-4 text-emerald-900 shadow-2xs flex items-center gap-3"
                         x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-sm font-medium">{{ session('success') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 rounded-lg border border-rose-200 bg-rose-50/80 p-4 text-rose-900 shadow-2xs">
                        <div class="flex items-center gap-2 mb-2 font-semibold text-sm text-rose-700">
                            <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Please check the errors below:</span>
                        </div>
                        <ul class="list-disc list-inside text-sm space-y-1 text-rose-800 pl-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')

    <script src="https://unpkg.com/trix@2.1.13/dist/trix.umd.min.js"></script>
</body>
</html>