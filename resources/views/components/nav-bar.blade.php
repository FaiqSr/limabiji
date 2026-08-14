<header class=" flex justify-between items-center px-6 lg:px-12 py-5 bg-surface">
    <nav>
        <a href="{{ url('/') }}">
            <img src="{{ asset('assets/images/logo/logo-lima-biji.webp') }}" alt="Lima Biji Agritech — specialty enzymatic civet coffee" class="h-12 sm:h-16 w-auto">
        </a>
    </nav>

    {{-- Desktop Nav --}}
    <nav class="gap-2 hidden lg:flex items-center">
        <a href="{{ url('/about') }}" class="nav-link">{{ __('nav.about') }}</a>
        <a href="{{ url('/innovation') }}" class="nav-link">{{ __('nav.innovation') }}</a>
        <a href="{{ url('/news') }}" class="nav-link">{{ __('nav.news') }}</a>
        <a href="{{ url('/testimonials') }}" class="nav-link">{{ __('nav.testimonials') }}</a>
        <a href="{{ url('/contact') }}" class="nav-link">{{ __('nav.contact') }}</a>

        {{-- Language Switcher --}}
        <div class="flex items-center gap-0.5 rounded-lg border border-border bg-surface-alt p-1 ml-2">
            <form method="POST" action="{{ route('locale.switch') }}" class="inline">
                @csrf
                <input type="hidden" name="locale" value="en">
                <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-medium transition-all duration-200 {{ app()->getLocale() === 'en' ? 'bg-primary text-dark shadow-sm' : 'text-dark/70 hover:text-dark' }}">
                    EN
                </button>
            </form>
            <form method="POST" action="{{ route('locale.switch') }}" class="inline">
                @csrf
                <input type="hidden" name="locale" value="id">
                <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-medium transition-all duration-200 {{ app()->getLocale() === 'id' ? 'bg-primary text-dark shadow-sm' : 'text-dark/70 hover:text-dark' }}">
                    ID
                </button>
            </form>
        </div>
    </nav>

    {{-- Hamburger — Mobile --}}
    <button id="sidebar-open" class="lg:hidden text-dark hover:text-primary transition-colors" aria-label="Open menu">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </button>
</header>

{{-- Overlay --}}
<div id="sidebar-overlay"></div>

{{-- Sidebar Panel --}}
<aside id="sidebar">
    <div class="sidebar-header">
        <a href="{{ url('/') }}">
            <img src="{{ asset('assets/images/logo/logo-lima-biji.webp') }}" alt="Lima Biji Agritech — specialty enzymatic civet coffee" class="h-10 w-auto">
        </a>
        <button id="sidebar-close" aria-label="Close menu">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <nav class="sidebar-nav">
        <a href="{{ url('/') }}" class="sidebar-link">
            <span>{{ __('nav.home') }}</span>
            <svg class="w-4 h-4 arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
        <a href="{{ url('/about') }}" class="sidebar-link">
            <span>{{ __('nav.about') }}</span>
            <svg class="w-4 h-4 arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
        <a href="{{ url('/innovation') }}" class="sidebar-link">
            <span>{{ __('nav.innovation') }}</span>
            <svg class="w-4 h-4 arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
        <a href="{{ url('/news') }}" class="sidebar-link">
            <span>{{ __('nav.news') }}</span>
            <svg class="w-4 h-4 arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
        <a href="{{ url('/testimonials') }}" class="sidebar-link">
            <span>{{ __('nav.testimonials') }}</span>
            <svg class="w-4 h-4 arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
        <a href="{{ url('/contact') }}" class="sidebar-link">
            <span>{{ __('nav.contact') }}</span>
            <svg class="w-4 h-4 arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
    </nav>

    {{-- Language Switcher - Mobile --}}
    <div class="px-6 py-4 border-t border-white/5">
        <div class="flex items-center gap-1 rounded-lg border border-white/10 bg-white/5 p-1">
            <form method="POST" action="{{ route('locale.switch') }}" class="flex-1">
                @csrf
                <input type="hidden" name="locale" value="en">
                <button type="submit" class="w-full px-3 py-2 rounded-lg text-xs font-medium transition-all duration-200 {{ app()->getLocale() === 'en' ? 'bg-primary text-dark shadow-sm' : 'text-white/70 hover:text-white' }}">
                    English
                </button>
            </form>
            <form method="POST" action="{{ route('locale.switch') }}" class="flex-1">
                @csrf
                <input type="hidden" name="locale" value="id">
                <button type="submit" class="w-full px-3 py-2 rounded-lg text-xs font-medium transition-all duration-200 {{ app()->getLocale() === 'id' ? 'bg-primary text-dark shadow-sm' : 'text-white/70 hover:text-white' }}">
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
        background: rgba(0,0,0,0.6);
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
        background: #0f1d19;
        border-left: 1px solid rgba(255,255,255,0.05);
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
        border-bottom: 1px solid rgba(255,255,255,0.05);
    }
    #sidebar-close {
        color: rgba(255,255,255,0.5);
        background: none;
        border: none;
        cursor: pointer;
        padding: 4px;
        transition: color 0.2s;
    }
    #sidebar-close:hover {
        color: #1a1a1a;
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
        color: rgba(255,255,255,0.7);
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .sidebar-link:hover {
        background: rgba(255,255,255,0.05);
        color: #1a1a1a;
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
        border-top: 1px solid rgba(255,255,255,0.05);
    }
    .sidebar-footer p:first-child {
        font-size: 12px;
        color: rgba(255,255,255,0.25);
    }
    .sidebar-footer p:last-child {
        font-size: 12px;
        color: rgba(255,255,255,0.15);
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