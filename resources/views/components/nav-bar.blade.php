<header class=" flex justify-between items-center px-6 lg:px-12 py-5 bg-surface">
    <nav>
        <a href="{{ url('/') }}">
            <img src="{{ asset('assets/images/logo/logo-lima-biji.webp') }}"
                alt="Lima Biji Agritech — specialty enzymatic civet coffee" class="h-12 sm:h-16 w-auto">
        </a>
    </nav>

    {{-- Desktop Nav --}}
    <nav class="gap-2 hidden lg:flex items-center">
        <a href="{{ url('/about') }}" class="nav-link {{ request()->is('about*') ? 'text-primary' : '' }}">{{ __('nav.about') }}</a>
        <a href="{{ url('/innovation') }}" class="nav-link {{ request()->is('innovation*') ? 'text-primary' : '' }}">{{ __('nav.innovation') }}</a>
        <a href="{{ route('store.index') }}" class="nav-link {{ request()->is('store*') ? 'text-primary' : '' }}">
            <span class="flex items-center gap-1.5">
                {{ __('nav.store') }}
            </span>
        </a>
        <a href="{{ url('/contact') }}" class="nav-link {{ request()->is('contact*') ? 'text-primary' : '' }}">{{ __('nav.contact') }}</a>
    </nav>

    {{-- Right Section: Cart & Language Switcher --}}
    <div class="flex items-center gap-3">
        {{-- Cart Icon Button --}}
        <button type="button" data-open-cart
            class="relative flex items-center justify-center w-10 h-10 rounded-xl bg-surface-alt border border-border/80 hover:border-primary/50 text-white hover:text-primary transition-all duration-200 cursor-pointer active:scale-95 shadow-sm"
            aria-label="View Cart">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
            <span class="cart-badge-count hidden absolute -top-1.5 -right-1.5 min-w-[1.25rem] h-5 px-1 rounded-full bg-primary text-white text-[10px] font-mono font-bold flex items-center justify-center shadow-md">
                0
            </span>
        </button>

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
        <button id="sidebar-open" class="lg:hidden text-white hover:text-primary transition-colors p-1" aria-label="Open menu">
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
                <span class="px-2 py-0.5 rounded-md bg-primary/20 text-[10px] uppercase font-mono font-bold text-primary">New</span>
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
