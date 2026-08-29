<header class=" flex justify-between items-center px-6 lg:px-12 py-5 bg-surface">
    <nav>
        <a href="{{ url('/') }}">
            <img src="{{ asset('assets/images/logo/logo-lima-biji.webp') }}"
                alt="Lima Biji Agritech — specialty enzymatic civet coffee" class="h-12 sm:h-16 w-auto">
        </a>
    </nav>

    {{-- Desktop Nav --}}
    <nav class="gap-2 hidden lg:flex items-center">
        <a href="{{ url('/about') }}"
            class="nav-link {{ request()->is('about*') ? 'text-primary' : '' }}">{{ __('nav.about') }}</a>
        <a href="{{ url('/innovation') }}"
            class="nav-link {{ request()->is('innovation*') ? 'text-primary' : '' }}">{{ __('nav.innovation') }}</a>
        <a href="{{ route('store.index') }}" class="nav-link {{ request()->is('store*') ? 'text-primary' : '' }}">
            <span class="flex items-center gap-1.5">
                {{ __('nav.store') }}
            </span>
        </a>
        <a href="{{ url('/contact') }}"
            class="nav-link {{ request()->is('contact*') ? 'text-primary' : '' }}">{{ __('nav.contact') }}</a>
    </nav>

    {{-- Right Section: Cart & Language Switcher --}}
    <div class="flex items-center gap-3">

        {{-- Language Switcher --}}
        <div class="hidden lg:flex items-center gap-0.5 rounded-lg border border-border bg-surface-alt p-1">
            <form method="POST" action="{{ route('locale.switch') }}" class="inline">
                @csrf
                <input type="hidden" name="locale" value="en">
                <button type="submit"
                    class="px-3 py-1.5 rounded-md text-xs font-medium transition-all duration-200 {{ app()->getLocale() === 'en' ? 'bg-primary text-white shadow-sm' : 'text-white/70 hover:text-white' }}">
                    EN
                </button>
            </form>
            <form method="POST" action="{{ route('locale.switch') }}" class="inline">
                @csrf
                <input type="hidden" name="locale" value="id">
                <button type="submit"
                    class="px-3 py-1.5 rounded-md text-xs font-medium transition-all duration-200 {{ app()->getLocale() === 'id' ? 'bg-primary text-white shadow-sm' : 'text-white/70 hover:text-white' }}">
                    ID
                </button>
            </form>
        </div>

        {{-- Hamburger — Mobile --}}
        <button id="sidebar-open" class="lg:hidden text-white hover:text-primary transition-colors p-1"
            aria-label="Open menu">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>
</header>

{{-- Overlay --}}
<div id="sidebar-overlay"></div>

{{-- Sidebar Panel --}}
<aside id="sidebar">
    <div class="sidebar-header">
        <a href="{{ url('/') }}">
            <img src="{{ asset('assets/images/logo/logo-lima-biji.webp') }}"
                alt="Lima Biji Agritech — specialty enzymatic civet coffee" class="h-10 w-auto">
        </a>
        <button id="sidebar-close" aria-label="Close menu">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <nav class="sidebar-nav">
        <a href="{{ url('/') }}" class="sidebar-link">
            <span>{{ __('nav.home') }}</span>
            <svg class="w-4 h-4 arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
        <a href="{{ url('/about') }}" class="sidebar-link">
            <span>{{ __('nav.about') }}</span>
            <svg class="w-4 h-4 arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
        <a href="{{ url('/innovation') }}" class="sidebar-link">
            <span>{{ __('nav.innovation') }}</span>
            <svg class="w-4 h-4 arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
        <a href="{{ route('store.index') }}" class="sidebar-link text-primary font-semibold">
            <span class="flex items-center gap-2">
                {{ __('nav.store') }}
                <span
                    class="px-2 py-0.5 rounded-md bg-primary/20 text-[10px] uppercase font-mono font-bold text-primary">New</span>
            </span>
            <svg class="w-4 h-4 arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
        <a href="{{ url('/news') }}" class="sidebar-link">
            <span>{{ __('nav.news') }}</span>
            <svg class="w-4 h-4 arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
        <a href="{{ url('/testimonials') }}" class="sidebar-link">
            <span>{{ __('nav.testimonials') }}</span>
            <svg class="w-4 h-4 arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
        <a href="{{ url('/contact') }}" class="sidebar-link">
            <span>{{ __('nav.contact') }}</span>
            <svg class="w-4 h-4 arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
    </nav>

    {{-- Language Switcher - Mobile --}}
    <div class="px-6 py-4 border-t border-white/5">
        <div class="flex items-center gap-1 rounded-lg border border-white/10 bg-white/5 p-1">
            <form method="POST" action="{{ route('locale.switch') }}" class="flex-1">
                @csrf
                <input type="hidden" name="locale" value="en">
                <button type="submit"
                    class="w-full px-3 py-2 rounded-lg text-xs font-medium transition-all duration-200 {{ app()->getLocale() === 'en' ? 'bg-primary text-white shadow-sm' : 'text-white/70 hover:text-white' }}">
                    English
                </button>
            </form>
            <form method="POST" action="{{ route('locale.switch') }}" class="flex-1">
                @csrf
                <input type="hidden" name="locale" value="id">
                <button type="submit"
                    class="w-full px-3 py-2 rounded-lg text-xs font-medium transition-all duration-200 {{ app()->getLocale() === 'id' ? 'bg-primary text-white shadow-sm' : 'text-white/70 hover:text-white' }}">
                    Indonesia
                </button>
            </form>
        </div>
    </div>

    <div class="sidebar-footer">
        <p>Lima Biji Agritech</p>
        <p>Bogor, West Java</p>
    </div>
</aside>
