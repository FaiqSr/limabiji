<!-- Sidebar backdrop for mobile -->
<div x-show="mobileOpen"
     x-transition:enter="transition-opacity ease-linear duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click="mobileOpen = false"
     class="fixed inset-0 z-40 bg-slate-900/40 backdrop-blur-xs lg:hidden"
     style="display: none;"></div>

<!-- Sidebar Container -->
<aside :class="mobileOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
       class="fixed top-0 left-0 z-50 h-full w-64 bg-white text-slate-700 border-r border-slate-200/90 flex flex-col transition-transform duration-200 ease-in-out shadow-xs">
    
    <!-- Header / Brand -->
    <div class="h-16 px-5 flex items-center justify-between border-b border-slate-100 shrink-0">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 group">
            <div class="w-8 h-8 rounded-lg bg-slate-900 text-white flex items-center justify-center font-bold text-xs shadow-2xs group-hover:bg-slate-800 transition-colors">
                LB
            </div>
            <div>
                <span class="text-sm font-bold text-slate-900 tracking-tight block leading-tight">Lima Biji</span>
                <span class="text-[10px] text-slate-400 font-mono tracking-wider uppercase font-semibold">Admin Portal</span>
            </div>
        </a>
        <button @click="mobileOpen = false" class="lg:hidden text-slate-400 hover:text-slate-700 p-1 rounded-md hover:bg-slate-100 transition-colors" aria-label="Close menu">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <!-- Navigation List (Accordion-enabled categories) -->
    <nav class="flex-1 px-3 py-3 space-y-2.5 overflow-y-auto">
        
        <!-- SECTION: Overview -->
        @php $isOverviewActive = request()->routeIs('admin.dashboard', 'admin.analytics.*'); @endphp
        <div x-data="{ open: {{ $isOverviewActive ? 'true' : 'true' }} }" class="space-y-1">
            <button @click="open = !open" type="button" class="w-full flex items-center justify-between px-2.5 py-1 text-[10px] font-bold text-slate-400 hover:text-slate-600 uppercase tracking-wider font-mono rounded select-none transition-colors group">
                <span class="group-hover:text-slate-700 transition-colors">Overview</span>
                <svg class="w-3 h-3 text-slate-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div x-show="open" x-collapse class="space-y-0.5 pt-0.5">
                <x-admin.nav-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    <span class="flex-1 text-xs">Dashboard</span>
                </x-admin.nav-link>

                <x-admin.nav-link href="{{ route('admin.analytics.index') }}" :active="request()->routeIs('admin.analytics.*')">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <span class="flex-1 text-xs">Analytics</span>
                </x-admin.nav-link>
            </div>
        </div>

        <!-- SECTION: Store Management (Products & Orders) -->
        @php $isStoreActive = request()->routeIs('admin.products.*', 'admin.orders.*'); @endphp
        <div x-data="{ open: {{ $isStoreActive ? 'true' : 'true' }} }" class="space-y-1">
            <button @click="open = !open" type="button" class="w-full flex items-center justify-between px-2.5 py-1 text-[10px] font-bold text-slate-400 hover:text-slate-600 uppercase tracking-wider font-mono rounded select-none transition-colors group">
                <span class="flex items-center gap-1.5 group-hover:text-slate-700 transition-colors">
                    Store
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                </span>
                <svg class="w-3 h-3 text-slate-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div x-show="open" x-collapse class="space-y-0.5 pt-0.5">
                <x-admin.nav-link href="{{ route('admin.products.index') }}" :active="request()->routeIs('admin.products.*')">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <span class="flex-1 text-xs">Products</span>
                </x-admin.nav-link>

                <x-admin.nav-link href="{{ route('admin.orders.index') }}" :active="request()->routeIs('admin.orders.*')">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    <span class="flex-1 text-xs">Orders</span>
                </x-admin.nav-link>
            </div>
        </div>

        <!-- SECTION: Agritech & Export -->
        @php $isAgritechActive = request()->routeIs('admin.origins.*', 'admin.export-destinations.*', 'admin.innovation-steps.*'); @endphp
        <div x-data="{ open: {{ $isAgritechActive ? 'true' : 'false' }} }" class="space-y-1">
            <button @click="open = !open" type="button" class="w-full flex items-center justify-between px-2.5 py-1 text-[10px] font-bold text-slate-400 hover:text-slate-600 uppercase tracking-wider font-mono rounded select-none transition-colors group">
                <span class="group-hover:text-slate-700 transition-colors">Agritech & Export</span>
                <svg class="w-3 h-3 text-slate-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div x-show="open" x-collapse class="space-y-0.5 pt-0.5">
                <x-admin.nav-link href="{{ route('admin.origins.index') }}" :active="request()->routeIs('admin.origins.*')">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2v1a2 2 0 002 2h2.945M15 19a2 2 0 01-2 2h-1a2 2 0 01-2-2v-1a2 2 0 00-2-2H8m11-9a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="flex-1 text-xs">Origins</span>
                </x-admin.nav-link>

                <x-admin.nav-link href="{{ route('admin.export-destinations.index') }}" :active="request()->routeIs('admin.export-destinations.*')">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                    </svg>
                    <span class="flex-1 text-xs">Export Map</span>
                </x-admin.nav-link>

                <x-admin.nav-link href="{{ route('admin.innovation-steps.index') }}" :active="request()->routeIs('admin.innovation-steps.*')">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                    </svg>
                    <span class="flex-1 text-xs">Process Steps</span>
                </x-admin.nav-link>
            </div>
        </div>

        <!-- SECTION: Content & Stories -->
        @php $isContentActive = request()->routeIs('admin.news.*', 'admin.categories.*', 'admin.testimonials.*', 'admin.certificates.*', 'admin.faqs.*'); @endphp
        <div x-data="{ open: {{ $isContentActive ? 'true' : 'false' }} }" class="space-y-1">
            <button @click="open = !open" type="button" class="w-full flex items-center justify-between px-2.5 py-1 text-[10px] font-bold text-slate-400 hover:text-slate-600 uppercase tracking-wider font-mono rounded select-none transition-colors group">
                <span class="group-hover:text-slate-700 transition-colors">Content & Stories</span>
                <svg class="w-3 h-3 text-slate-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div x-show="open" x-collapse class="space-y-0.5 pt-0.5">
                <x-admin.nav-link href="{{ route('admin.news.index') }}" :active="request()->routeIs('admin.news.*')">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                    <span class="flex-1 text-xs">News</span>
                    @php $pendingCount = \App\Models\Article::where('status', 'pending')->count(); @endphp
                    @if ($pendingCount > 0)
                        <span class="bg-amber-50 text-amber-800 text-[10px] font-bold px-1.5 py-0.5 rounded border border-amber-200 font-mono">{{ $pendingCount }}</span>
                    @endif
                </x-admin.nav-link>

                <x-admin.nav-link href="{{ route('admin.categories.index') }}" :active="request()->routeIs('admin.categories.*')">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    <span class="flex-1 text-xs">Categories</span>
                </x-admin.nav-link>

                <x-admin.nav-link href="{{ route('admin.testimonials.index') }}" :active="request()->routeIs('admin.testimonials.*')">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    <span class="flex-1 text-xs">Testimonials</span>
                </x-admin.nav-link>

                <x-admin.nav-link href="{{ route('admin.certificates.index') }}" :active="request()->routeIs('admin.certificates.*')">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                    <span class="flex-1 text-xs">Certificates</span>
                </x-admin.nav-link>

                <x-admin.nav-link href="{{ route('admin.faqs.index') }}" :active="request()->routeIs('admin.faqs.*')">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="flex-1 text-xs">FAQs</span>
                </x-admin.nav-link>
            </div>
        </div>

        <!-- SECTION: Inquiries & Media -->
        @php $isCommunicationActive = request()->routeIs('admin.messages.*', 'admin.media.*'); @endphp
        <div x-data="{ open: {{ $isCommunicationActive ? 'true' : 'false' }} }" class="space-y-1">
            <button @click="open = !open" type="button" class="w-full flex items-center justify-between px-2.5 py-1 text-[10px] font-bold text-slate-400 hover:text-slate-600 uppercase tracking-wider font-mono rounded select-none transition-colors group">
                <span class="group-hover:text-slate-700 transition-colors">Inquiries & Media</span>
                <svg class="w-3 h-3 text-slate-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div x-show="open" x-collapse class="space-y-0.5 pt-0.5">
                <x-admin.nav-link href="{{ route('admin.messages.index') }}" :active="request()->routeIs('admin.messages.*')">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <span class="flex-1 text-xs">Messages</span>
                    @php $unreadMessagesCount = \App\Models\ContactMessage::where('is_read', false)->count(); @endphp
                    @if ($unreadMessagesCount > 0)
                        <span class="bg-indigo-50 text-indigo-700 text-[10px] font-bold px-1.5 py-0.5 rounded border border-indigo-200 font-mono">{{ $unreadMessagesCount }}</span>
                    @endif
                </x-admin.nav-link>

                <x-admin.nav-link href="{{ route('admin.media.index') }}" :active="request()->routeIs('admin.media.*')">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="flex-1 text-xs">Media</span>
                </x-admin.nav-link>
            </div>
        </div>

        <!-- SECTION: System -->
        @php $isSystemActive = request()->routeIs('admin.settings.*', 'admin.users.*'); @endphp
        <div x-data="{ open: {{ $isSystemActive ? 'true' : 'false' }} }" class="space-y-1">
            <button @click="open = !open" type="button" class="w-full flex items-center justify-between px-2.5 py-1 text-[10px] font-bold text-slate-400 hover:text-slate-600 uppercase tracking-wider font-mono rounded select-none transition-colors group">
                <span class="group-hover:text-slate-700 transition-colors">System</span>
                <svg class="w-3 h-3 text-slate-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div x-show="open" x-collapse class="space-y-0.5 pt-0.5">
                <x-admin.nav-link href="{{ route('admin.settings.index') }}" :active="request()->routeIs('admin.settings.*')">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="flex-1 text-xs">Settings</span>
                </x-admin.nav-link>

                @if (auth()->user()?->role === 'admin')
                <x-admin.nav-link href="{{ route('admin.users.index') }}" :active="request()->routeIs('admin.users.*')">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span class="flex-1 text-xs">Users</span>
                </x-admin.nav-link>
                @endif
            </div>
        </div>

    </nav>

    <!-- Footer Profile / Action -->
    <div class="p-3 border-t border-slate-100 bg-slate-50/50 shrink-0 space-y-1">
        <a href="{{ url('/') }}" target="_blank" class="flex items-center gap-2.5 px-3 py-2 text-xs font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition-colors">
            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
            <span>Live Website</span>
        </a>
        
        <form action="{{ route('admin.logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full flex items-center gap-2.5 px-3 py-2 text-xs font-medium text-rose-600 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span>Sign Out</span>
            </button>
        </form>
    </div>
</aside>