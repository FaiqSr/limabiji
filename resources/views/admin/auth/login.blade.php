<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Sign In — Lima Biji</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/admin.css'])
</head>
<body class="font-body bg-slate-50 text-slate-900 antialiased min-h-screen flex items-center justify-center p-4 sm:p-6">

    <div class="w-full max-w-md">
        <!-- Logo & Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-lg bg-slate-900 text-white font-bold mb-3 shadow-2xs">
                <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Lima Biji Admin</h1>
            <p class="text-slate-500 text-xs mt-1">Sign in to access the management portal</p>
        </div>

        <!-- Login Card -->
        <div class="card-modern bg-white text-slate-900 border border-slate-200 p-6 sm:p-8 rounded-lg shadow-2xs">
            <h2 class="text-lg font-bold text-slate-900 mb-6">Sign In to Your Account</h2>

            @if ($errors->any())
                <div class="rounded-lg border border-rose-200 bg-rose-50 p-3.5 mb-6 text-rose-800 text-sm flex items-center gap-2">
                    <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form action="{{ route('admin.login') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label for="email" class="text-xs font-semibold uppercase tracking-wider text-slate-600">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus placeholder="admin@limabiji.com" class="mt-1">
                </div>

                <div>
                    <label for="password" class="text-xs font-semibold uppercase tracking-wider text-slate-600">Password</label>
                    <input type="password" name="password" id="password" required placeholder="••••••••" class="mt-1">
                </div>

                <button type="submit" class="btn btn-primary w-full py-2.5 text-sm font-semibold shadow-2xs">
                    <span>Sign In</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </form>
        </div>

        <p class="text-center text-xs text-slate-500 mt-6">
            &copy; {{ date('Y') }} Lima Biji Specialty Coffee. All rights reserved.
        </p>
    </div>

</body>
</html>