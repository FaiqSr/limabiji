{{-- resources/views/components/store/nav-bar.blade.php --}}
@php
    $locale = app()->getLocale();
@endphp

<header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-200 shadow-xs transition-all">
    <div class="container mx-auto px-5 lg:px-8 h-20 flex justify-between items-center max-w-7xl">
        {{-- Brand Logo --}}
        <div class="flex items-center gap-6">
            <a href="{{ route('store.index') }}" class="flex items-center gap-3 group">
                <img src="{{ asset('assets/images/logo/logo-lima-biji.webp') }}"
                    alt="Lima Biji Roastery & Store" class="h-10 sm:h-12 w-auto">
                <div class="hidden sm:block border-l border-slate-200 pl-3">
                    <span class="font-display text-lg text-slate-900 leading-none tracking-wider block uppercase">Lima Biji</span>
                    <span class="text-[10px] font-mono font-bold text-primary tracking-widest uppercase block mt-0.5">STORE</span>
                </div>
            </a>
        </div>

        {{-- Right Section: Cart, Language Switcher, and Mobile Toggle --}}
        <div class="flex items-center gap-3">
            {{-- Cart Button with Badge --}}
            <button type="button" data-open-cart
                class="relative flex items-center justify-center gap-2 px-3.5 py-2.5 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-800 hover:text-primary transition-all duration-200 cursor-pointer active:scale-95 shadow-xs"
                aria-label="View Cart">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                <span class="hidden sm:inline text-xs font-bold uppercase tracking-wider">{{ $locale === 'id' ? 'Keranjang' : 'Cart' }}</span>
                <span class="cart-badge-count hidden min-w-[1.25rem] h-5 px-1.5 rounded-full bg-primary text-white text-[11px] font-mono font-bold flex items-center justify-center shadow-xs">
                    0
                </span>
            </button>

            {{-- Language Switcher --}}
            <div class="hidden lg:flex items-center gap-0.5 rounded-xl border border-slate-200 bg-slate-50 p-1">
                <form method="POST" action="{{ route('locale.switch') }}" class="inline">
                    @csrf
                    <input type="hidden" name="locale" value="en">
                    <button type="submit"
                        class="px-2.5 py-1.5 rounded-lg text-xs font-bold transition-all duration-200 cursor-pointer {{ $locale === 'en' ? 'bg-primary text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                        EN
                    </button>
                </form>
                <form method="POST" action="{{ route('locale.switch') }}" class="inline">
                    @csrf
                    <input type="hidden" name="locale" value="id">
                    <button type="submit"
                        class="px-2.5 py-1.5 rounded-lg text-xs font-bold transition-all duration-200 cursor-pointer {{ $locale === 'id' ? 'bg-primary text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                        ID
                    </button>
                </form>
            </div>

            {{-- Mobile Sidebar Open Button --}}
            <button id="store-sidebar-open" class="lg:hidden p-2 rounded-xl bg-slate-50 border border-slate-200 text-slate-800 hover:text-primary transition-colors cursor-pointer" aria-label="Open menu">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </div>
</header>

{{-- Mobile Sidebar Drawer for Store --}}
<div id="store-sidebar-overlay" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-[85] opacity-0 pointer-events-none transition-opacity duration-300"></div>

<aside id="store-sidebar" class="fixed top-0 right-0 bottom-0 w-80 max-w-full bg-white border-l border-slate-200 shadow-2xl z-[90] translate-x-full transition-transform duration-300 ease-out flex flex-col">
    <div class="px-6 py-5 border-b border-slate-200 flex items-center justify-between bg-slate-50">
        <a href="{{ route('store.index') }}">
            <img src="{{ asset('assets/images/logo/logo-lima-biji.webp') }}"
                alt="Lima Biji Roastery" class="h-9 w-auto">
        </a>
        <button id="store-sidebar-close" aria-label="Close menu"
            class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-500 hover:text-slate-900 flex items-center justify-center transition-colors cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <nav class="p-6 space-y-2 flex-1 overflow-y-auto">
        <a href="{{ route('store.index') }}"
            class="flex items-center justify-between p-3 rounded-xl font-semibold text-sm {{ request()->routeIs('store.index') ? 'bg-primary/10 text-primary' : 'text-slate-800 hover:bg-slate-50' }}">
            <span>{{ $locale === 'id' ? 'Katalog Kopi' : 'Coffee Catalog' }}</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>

        <button type="button" data-open-quiz onclick="document.getElementById('store-sidebar-close').click()"
            class="w-full flex items-center justify-between p-3 rounded-xl font-semibold text-sm text-slate-800 hover:bg-slate-50 text-left cursor-pointer">
            <span class="flex items-center gap-2">
                <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                <span>{{ $locale === 'id' ? 'Kuis Rekomendasi Kopi' : 'Coffee Matcher Quiz' }}</span>
            </span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </button>

        <a href="{{ url('/') }}" class="flex items-center justify-between p-3 rounded-xl font-semibold text-sm text-slate-800 hover:bg-slate-50">
            <span>{{ __('nav.home') }}</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>

        <a href="{{ url('/about') }}" class="flex items-center justify-between p-3 rounded-xl font-semibold text-sm text-slate-800 hover:bg-slate-50">
            <span>{{ __('nav.about') }}</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>

        <a href="{{ url('/innovation') }}" class="flex items-center justify-between p-3 rounded-xl font-semibold text-sm text-slate-800 hover:bg-slate-50">
            <span>{{ __('nav.innovation') }}</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>

        <a href="{{ url('/contact') }}" class="flex items-center justify-between p-3 rounded-xl font-semibold text-sm text-slate-800 hover:bg-slate-50">
            <span>{{ __('nav.contact') }}</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
    </nav>

    {{-- Mobile Language Switcher --}}
    <div class="p-6 border-t border-slate-200 bg-slate-50">
        <div class="flex items-center gap-2">
            <form method="POST" action="{{ route('locale.switch') }}" class="flex-1">
                @csrf
                <input type="hidden" name="locale" value="en">
                <button type="submit"
                    class="w-full py-2.5 rounded-xl text-xs font-bold transition-all {{ $locale === 'en' ? 'bg-primary text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-700' }}">
                    English
                </button>
            </form>
            <form method="POST" action="{{ route('locale.switch') }}" class="flex-1">
                @csrf
                <input type="hidden" name="locale" value="id">
                <button type="submit"
                    class="w-full py-2.5 rounded-xl text-xs font-bold transition-all {{ $locale === 'id' ? 'bg-primary text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-700' }}">
                    Indonesia
                </button>
            </form>
        </div>
    </div>
</aside>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const openBtn = document.getElementById('store-sidebar-open');
    const closeBtn = document.getElementById('store-sidebar-close');
    const overlay = document.getElementById('store-sidebar-overlay');
    const sidebar = document.getElementById('store-sidebar');

    function openStoreSidebar() {
        if (overlay && sidebar) {
            overlay.classList.remove('opacity-0', 'pointer-events-none');
            sidebar.classList.remove('translate-x-full');
        }
    }

    function closeStoreSidebar() {
        if (overlay && sidebar) {
            overlay.classList.add('opacity-0', 'pointer-events-none');
            sidebar.classList.add('translate-x-full');
        }
    }

    if (openBtn) openBtn.addEventListener('click', openStoreSidebar);
    if (closeBtn) closeBtn.addEventListener('click', closeStoreSidebar);
    if (overlay) overlay.addEventListener('click', closeStoreSidebar);
});
</script>
