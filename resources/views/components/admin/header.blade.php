<header class="sticky top-0 z-30 bg-white/95 backdrop-blur-md border-b border-slate-200/80 px-4 sm:px-6 lg:px-8 py-3 flex items-center justify-between shadow-2xs">
    <div class="flex items-center gap-3">
        <!-- Mobile Menu Toggle Button -->
        <button @click="mobileOpen = true" class="lg:hidden text-slate-500 hover:text-slate-700 p-2 rounded-lg hover:bg-slate-100 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        <div>
            <h1 class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight">
                @yield('page_title', 'Dashboard')
            </h1>
            <p class="text-[11px] text-slate-500 hidden sm:block">Lima Biji Agritech — Operations & Content Management</p>
        </div>
    </div>

    <div class="flex items-center gap-3 sm:gap-4">
        <!-- Quick Website Link Button -->
        <a href="{{ url('/') }}" target="_blank" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-slate-600 hover:text-slate-900 hover:bg-slate-50 text-xs font-medium transition-all">
            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
            <span>Live Site</span>
        </a>

        <div class="h-6 w-px bg-slate-200 hidden sm:block"></div>

        <!-- User Info & Avatar -->
        <div class="flex items-center gap-3">
            <div class="text-right hidden sm:block">
                <p class="text-xs font-semibold text-slate-900 leading-tight">{{ auth()->user()?->name ?? 'Admin User' }}</p>
                <span class="inline-block px-1.5 py-0.5 rounded-lg text-[10px] font-semibold uppercase bg-slate-100 text-slate-600 border border-slate-200">
                    {{ auth()->user()?->role ?? 'Administrator' }}
                </span>
            </div>
            <div class="w-8 h-8 rounded-lg bg-slate-900 text-white flex items-center justify-center font-bold text-xs">
                {{ strtoupper(substr(auth()->user()?->name ?? 'A', 0, 1)) }}
            </div>
        </div>
    </div>
</header>