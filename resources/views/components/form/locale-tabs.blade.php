@props([
    'title' => 'Content & Translations',
    'subtitle' => null,
    'defaultLocale' => 'en',
    'tabId' => 'locale-' . uniqid(),
])

<div class="card-modern space-y-4" x-data="{ locale: '{{ $defaultLocale }}' }">
    <div class="flex items-center justify-between pb-3 border-b border-slate-100 gap-3">
        <div>
            <h3 class="text-base font-semibold text-slate-900">{{ $title }}</h3>
            @if ($subtitle)
                <p class="text-xs text-slate-500 mt-0.5">{{ $subtitle }}</p>
            @endif
        </div>

        {{-- WAI-ARIA Tablist --}}
        <div class="inline-flex bg-slate-100 p-0.5 rounded-lg border border-slate-200" 
             role="tablist" 
             aria-label="Language Translation Tabs">
            
            <button type="button" 
                    @click="locale = 'en'" 
                    role="tab"
                    id="{{ $tabId }}-tab-en"
                    :aria-selected="locale === 'en' ? 'true' : 'false'"
                    :tabindex="locale === 'en' ? '0' : '-1'"
                    aria-controls="{{ $tabId }}-panel-en"
                    :class="locale === 'en' ? 'bg-white text-indigo-600 shadow-2xs font-semibold' : 'text-slate-600 hover:text-slate-900'" 
                    class="px-2.5 py-1 text-xs rounded-md transition-all flex items-center gap-1.5 focus:ring-2 focus:ring-indigo-500 focus:outline-hidden">
                <span class="text-[10px] font-bold uppercase tracking-wider px-1 bg-slate-200 rounded text-slate-700">EN</span>
                <span>English</span>
            </button>

            <button type="button" 
                    @click="locale = 'id'" 
                    role="tab"
                    id="{{ $tabId }}-tab-id"
                    :aria-selected="locale === 'id' ? 'true' : 'false'"
                    :tabindex="locale === 'id' ? '0' : '-1'"
                    aria-controls="{{ $tabId }}-panel-id"
                    :class="locale === 'id' ? 'bg-emerald-600 text-white shadow-2xs font-semibold' : 'text-slate-600 hover:text-slate-900'" 
                    class="px-2.5 py-1 text-xs rounded-md transition-all flex items-center gap-1.5 focus:ring-2 focus:ring-emerald-500 focus:outline-hidden">
                <span class="text-[10px] font-bold uppercase tracking-wider px-1 bg-emerald-700/60 rounded text-white">ID</span>
                <span>Bahasa</span>
            </button>
        </div>
    </div>

    {{-- English Content Panel --}}
    <div x-show="locale === 'en'"
         x-cloak
         role="tabpanel"
         id="{{ $tabId }}-panel-en"
         aria-labelledby="{{ $tabId }}-tab-en"
         class="space-y-4">
        {{ $en ?? $slot }}
    </div>

    {{-- Indonesian Content Panel --}}
    <div x-show="locale === 'id'"
         x-cloak
         role="tabpanel"
         id="{{ $tabId }}-panel-id"
         aria-labelledby="{{ $tabId }}-tab-id"
         class="space-y-4">
        {{ $id ?? '' }}
    </div>
</div>
