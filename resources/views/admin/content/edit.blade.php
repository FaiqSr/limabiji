@extends('admin.layouts.app')
@section('title', 'Edit ' . $page->title)
@section('page_title', 'CMS Content Editor: ' . $page->title)

@section('content')
<div x-data="blockEditor({{ \Illuminate\Support\Js::from($page->blocks) }}, {
        previewUrl: '{{ route('admin.content.preview') }}',
        mediaUrl: '{{ route('admin.media.index') }}',
        mediaUploadUrl: '{{ route('admin.media.upload') }}',
        csrfToken: '{{ csrf_token() }}',
        pageTitle: @js($page->title),
        metaDescription: @js($page->meta_description ?? ''),
        slug: @js($page->slug)
     })" 
     class="space-y-6 relative">

    <form action="{{ route('admin.content.update', $page) }}" method="POST" id="page-editor-form" @submit="isDirty = false">
        @csrf
        @method('PUT')

        <!-- Page Meta Header Bar -->
        <div class="card-modern flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.content.index') }}" 
                   class="p-2 rounded-lg border border-slate-200 text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-colors"
                   aria-label="Back to pages list">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                        <span>Editing Page: {{ $page->title }}</span>
                        <span class="text-xs font-mono px-2 py-0.5 rounded bg-slate-100 text-slate-700 border border-slate-200">/{{ $page->slug }}</span>
                    </h2>
                    <p class="text-xs text-slate-600">Modify dynamic blocks with real-time isolated iframe live preview on the right.</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <!-- Page Layout Presets Button -->
                <button type="button" 
                        @click="presetModalOpen = true" 
                        class="btn btn-secondary py-2 px-3 text-xs font-semibold flex items-center gap-1.5 shadow-2xs"
                        aria-label="Open page layout presets modal">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/>
                    </svg>
                    <span>Presets</span>
                </button>

                <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 bg-slate-50 px-3 py-2 rounded-lg border border-slate-200 cursor-pointer select-none">
                    <input type="checkbox" name="is_published" value="1" {{ $page->is_published ? 'checked' : '' }} class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 border-slate-300">
                    <span>Published</span>
                </label>

                <button type="submit" class="btn btn-primary py-2 px-4 text-xs font-bold shadow-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Save</span>
                </button>
            </div>
        </div>

        <!-- SEO Google & Social Share Card Preview Collapsible Box -->
        @include('admin.content.partials.seo-preview')

        <!-- Split Editor Grid: Left = Input Editor, Right = Isolated Iframe Live Preview -->
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-start">
            
            <!-- LEFT COLUMN: Form Editor Inputs (XL: 6 cols) -->
            <div class="xl:col-span-6 space-y-6">
                <div class="card-modern relative">
                    
                    <!-- Top Sticky Toolbar: Language Selector + View Mode + Search Filter -->
                    <div class=" bg-white/95 backdrop-blur-xs pt-1 pb-3 mb-6 border-b border-slate-100 space-y-3">
                        <div class="flex flex-wrap items-center justify-between gap-3 bg-slate-100 p-2.5 rounded-lg border border-slate-200">
                            <div class="flex flex-col" role="group" aria-label="Language Mode Selector">
                                <span class="text-xs font-bold text-slate-700 uppercase tracking-wider px-1">Language:</span>
                                <div class="flex gap-1 bg-slate-100 p-1 rounded-lg border border-slate-200">
                                    <button type="button"
                                            @click="switchLanguage('en')"
                                            :class="activeTab === 'en' ? 'bg-white text-indigo-600 shadow-2xs font-bold border border-slate-200' : 'text-slate-600 hover:text-slate-900 font-medium'"
                                            class="px-3 py-1.5 text-xs rounded-lg transition-all flex items-center gap-1.5 focus:ring-2 focus:ring-indigo-500 focus:outline-hidden"
                                            aria-label="Switch to English mode">
                                        <span class="text-[10px] font-bold uppercase tracking-wider px-1 bg-slate-200 rounded">EN</span>
                                        <span>English</span>
                                    </button>
                                    <button type="button"
                                            @click="switchLanguage('id')"
                                            :class="activeTab === 'id' ? 'bg-emerald-600 text-white shadow-2xs font-bold' : 'text-slate-600 hover:text-slate-900 font-medium'"
                                            class="px-3 py-1.5 text-xs rounded-lg transition-all flex items-center gap-1.5 focus:ring-2 focus:ring-emerald-500 focus:outline-hidden"
                                            aria-label="Switch to Indonesian mode">
                                        <span class="text-[10px] font-bold uppercase tracking-wider px-1 bg-emerald-700 text-white rounded">ID</span>
                                        <span>Indonesia</span>
                                    </button>
                                    <button type="button"
                                            @click="switchLanguage('split')"
                                            :class="activeTab === 'split' ? 'bg-indigo-600 text-white shadow-2xs font-bold' : 'text-slate-600 hover:text-slate-900 font-medium'"
                                            class="px-3 py-1.5 text-xs rounded-lg transition-all flex items-center gap-1.5 focus:ring-2 focus:ring-indigo-500 focus:outline-hidden"
                                            title="Edit EN and ID side-by-side simultaneously"
                                            aria-label="Switch to Split view mode">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7"/>
                                        </svg>
                                        <span>Split View</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Mini-Map Outline Drawer Button -->
                            <button type="button" 
                                    @click="outlineOpen = !outlineOpen" 
                                    class="btn btn-secondary py-1 px-2.5 text-xs flex items-center gap-1"
                                    :aria-expanded="outlineOpen"
                                    aria-label="Toggle Block Navigator Mini-Map">
                                <svg class="w-3.5 h-3.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                                </svg>
                                <span>Outline Mini-Map</span>
                            </button>
                        </div>

                        <!-- Real-Time Block Search Filter Bar -->
                        <div class="relative">
                            {{-- <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div> --}}
                            <input type="text" 
                                   x-model="searchQuery" 
                                   placeholder="Filter blocks by type or title..." 
                                   class="text-xs w-full pl-9 pr-8 py-2 border border-slate-200 rounded-lg bg-slate-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:outline-hidden"
                                   aria-label="Search content blocks">
                            <button type="button" 
                                    x-show="searchQuery" 
                                    @click="searchQuery = ''" 
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-xs text-slate-400 hover:text-slate-600"
                                    aria-label="Clear search query">✕</button>
                        </div>
                    </div>

                    <!-- Floating Outline Mini-Map Drawer -->
                    <div x-show="outlineOpen" x-transition class="p-3 bg-slate-50 border border-slate-200 rounded-xl mb-6 space-y-2">
                        <div class="flex items-center justify-between text-xs font-bold text-slate-700 uppercase tracking-wider">
                            <span>Block Navigator Mini-Map</span>
                            <button type="button" @click="outlineOpen = false" class="text-slate-400 hover:text-slate-600" aria-label="Close mini-map">✕</button>
                        </div>
                        <div class="flex flex-wrap gap-1.5">
                            <template x-for="(block, idx) in blocks" :key="block.id">
                                <button type="button" 
                                        @click="scrollToBlock(block.id)" 
                                        class="px-2 py-1 text-[11px] font-semibold rounded border border-slate-200 bg-white hover:bg-indigo-50 hover:text-indigo-600 transition-colors flex items-center gap-1 focus:ring-2 focus:ring-indigo-500 focus:outline-hidden">
                                    <span class="font-mono text-[10px] text-slate-500" x-text="'#' + (idx + 1)"></span>
                                    <span x-text="label(block)"></span>
                                </button>
                            </template>
                        </div>
                    </div>

                    <!-- Add Block Toolbar (Opens Palette Modal & Expand/Collapse All) -->
                    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
                        <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">Content Section Blocks:</label>
                        <div class="flex items-center gap-2">
                            <button type="button" 
                                    @click="toggleExpandAll()" 
                                    class="btn btn-ghost border border-slate-200 bg-white hover:bg-slate-50 py-1.5 px-3 text-xs font-semibold text-slate-700 flex items-center gap-1.5 shadow-2xs"
                                    :aria-label="isAllExpanded ? 'Collapse all blocks' : 'Expand all blocks'">
                                <svg class="w-3.5 h-3.5 text-slate-500 transition-transform duration-200" :class="{'rotate-180': isAllExpanded}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                                <span x-text="isAllExpanded ? 'Collapse All' : 'Expand All'"></span>
                            </button>

                            <button type="button" 
                                    @click="paletteModalOpen = true" 
                                    class="btn btn-primary py-1.5 px-3 text-xs font-bold flex items-center gap-1.5 shadow-2xs"
                                    aria-label="Open block palette modal">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                <span>Add Block Component</span>
                            </button>
                        </div>
                    </div>

                    <!-- Block List Editor Container (SortableJS Target) -->
                    <div class="space-y-4" id="block-list-container">
                        <template x-for="(block, index) in filteredBlocks" :key="block.id">
                            <div :id="'block-card-' + block.id" 
                                 class="rounded-xl border border-slate-200 bg-white p-4 shadow-2xs transition-all duration-200 block-card"
                                 :class="{'opacity-60 bg-slate-50': !block.is_visible}">
                                
                                <!-- Block Header & Quick Actions -->
                                <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-3">
                                    <div class="flex items-center gap-2">
                                        <!-- SortableJS Handle -->
                                        <div class="drag-handle cursor-grab hover:bg-slate-100 p-1 rounded text-slate-400 hover:text-slate-700 transition-colors" 
                                             title="Drag to reorder block"
                                             role="button"
                                             tabindex="0"
                                             aria-label="Drag handle to reorder block">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
                                            </svg>
                                        </div>

                                        <span class="font-bold text-xs uppercase tracking-wider text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-100" x-text="label(block)"></span>
                                        <span class="text-[10px] font-bold px-1.5 py-0.5 rounded border"
                                              :class="activeTab === 'en' ? 'bg-indigo-50 text-indigo-700 border-indigo-200' : (activeTab === 'id' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-purple-50 text-purple-700 border-purple-200')"
                                              x-text="activeTab === 'split' ? 'SPLIT (EN & ID)' : (activeTab === 'en' ? 'EN' : 'ID')"></span>
                                        <span class="text-[11px] text-slate-500 truncate max-w-[140px]" x-text="summary(block)"></span>
                                    </div>

                                    <div class="flex items-center gap-1">
                                        <!-- Toggle Visibility -->
                                        <button type="button" 
                                                @click="toggleVisibility(block)" 
                                                class="p-1 rounded text-slate-400 hover:text-slate-700 transition-colors" 
                                                :title="block.is_visible ? 'Hide Block' : 'Show Block'"
                                                :aria-label="block.is_visible ? 'Hide block from live page' : 'Show block on live page'">
                                            <svg x-show="block.is_visible" class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            <svg x-show="!block.is_visible" class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a8.88 8.88 0 012.122-.363c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21M3 3l18 18"/></svg>
                                        </button>

                                        <!-- Collapse/Expand -->
                                        <button type="button" 
                                                @click="block.collapsed = !block.collapsed" 
                                                class="p-1 rounded text-slate-400 hover:text-slate-700 transition-colors"
                                                :aria-label="block.collapsed ? 'Expand block form' : 'Collapse block form'">
                                            <svg class="w-4 h-4 transition-transform duration-200" :class="{'rotate-180': !block.collapsed}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </button>

                                        <!-- Duplicate -->
                                        <button type="button" 
                                                @click="duplicateBlock(index)" 
                                                class="p-1 text-slate-400 hover:text-indigo-600 transition-colors" 
                                                title="Duplicate block"
                                                aria-label="Duplicate block">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                            </svg>
                                        </button>

                                        <!-- Delete -->
                                        <button type="button" 
                                                @click="removeBlock(index)" 
                                                class="p-1 text-rose-500 hover:text-rose-700 transition-colors" 
                                                title="Remove Block"
                                                aria-label="Remove block">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Block Form Content -->
                                <div x-show="!block.collapsed">
                                    <!-- Hidden Inputs for Backend Persistence -->
                                    <input type="hidden" :name="'blocks['+index+'][id]'" :value="block.id">
                                    <input type="hidden" :name="'blocks['+index+'][block_type]'" :value="block.block_type">
                                    <input type="hidden" :name="'blocks['+index+'][order]'" :value="index">
                                    <input type="hidden" :name="'blocks['+index+'][is_visible]'" :value="block.is_visible ? 1 : 0">
                                    <input type="hidden" :name="'blocks['+index+'][content][en][_preserve]'" value="1">
                                    <input type="hidden" :name="'blocks['+index+'][content][id][_preserve]'" value="1">

                                    <!-- Single-Language Mode (EN or ID) -->
                                    <template x-for="loc in ['en', 'id']" :key="loc">
                                        <div x-show="activeTab === loc" class="space-y-3 pt-2">
                                            @include('admin.content.partials.block-form')
                                        </div>
                                    </template>

                                    <!-- Dual-Pane Split View Mode (EN and ID Side-by-Side) -->
                                    <div x-show="activeTab === 'split'" class="grid grid-cols-1 lg:grid-cols-2 gap-4 pt-3 border-t border-slate-100 mt-2">
                                        <!-- EN Column (Indigo Accent) -->
                                        <div x-data="{ loc: 'en' }" class="space-y-3 bg-indigo-50/40 p-3.5 rounded-xl border border-indigo-100 shadow-2xs">
                                            <div class="flex items-center justify-between border-b border-indigo-200/80 pb-2">
                                                <span class="text-xs font-bold uppercase tracking-wider text-indigo-800 flex items-center gap-1.5">
                                                    <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                                                    English (EN)
                                                </span>
                                                <span class="text-[10px] font-mono text-indigo-600 bg-indigo-100/70 px-1.5 py-0.5 rounded">Source / Default</span>
                                            </div>
                                            @include('admin.content.partials.block-form')
                                        </div>

                                        <!-- ID Column (Emerald Accent) -->
                                        <div x-data="{ loc: 'id' }" class="space-y-3 bg-emerald-50/40 p-3.5 rounded-xl border border-emerald-100 shadow-2xs">
                                            <div class="flex items-center justify-between border-b border-emerald-200/80 pb-2">
                                                <span class="text-xs font-bold uppercase tracking-wider text-emerald-800 flex items-center gap-1.5">
                                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                                    Indonesia (ID)
                                                </span>
                                                <span class="text-[10px] font-mono text-emerald-600 bg-emerald-100/70 px-1.5 py-0.5 rounded">Translation</span>
                                            </div>
                                            @include('admin.content.partials.block-form')
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: Real-Time Isolated Iframe Live Preview (XL: 6 cols, Sticky) -->
            <div class="xl:col-span-6 sticky top-20 space-y-4" :class="{'fixed inset-0 z-50 bg-white p-6 overflow-auto xl:col-span-12': isFullscreen}">
                <div class="card-modern p-4">
                    <!-- Responsive Viewport Switcher & Speed Indicator Header -->
                    <div class="flex flex-wrap items-center justify-between border-b border-slate-100 pb-3 mb-4 gap-2">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-700">Live Preview:</span>
                            <span class="text-xs font-mono px-2 py-0.5 rounded bg-slate-100 text-slate-700 border border-slate-200" 
                                  x-text="activeTab === 'en' ? 'EN Mode' : (activeTab === 'id' ? 'ID Mode' : 'Split View')"></span>
                            
                            <!-- Visual Syncing Pulse & Spinner Indicator -->
                            <span x-show="isSyncing" class="inline-flex items-center gap-1.5 text-[10px] font-bold text-amber-700 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded-full animate-pulse">
                                <svg class="w-3 h-3 animate-spin text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                </svg>
                                <span>Syncing...</span>
                            </span>

                            <!-- Sync Error Warning -->
                            <span x-show="syncError" class="text-[10px] font-bold text-rose-700 bg-rose-50 border border-rose-200 px-2 py-0.5 rounded-full flex items-center gap-1">
                                <svg class="w-3 h-3 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Preview Sync Error</span>
                            </span>
                        </div>

                        <!-- Page Weight & Load Speed Indicator -->
                        <div class="flex items-center gap-2 text-[11px] font-mono text-slate-600 bg-slate-50 px-2.5 py-1 rounded-lg border border-slate-200">
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                <span>~0.4s</span>
                            </span>
                            <span>•</span>
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span x-text="blocks.length + ' blocks'"></span>
                            </span>
                        </div>

                        <!-- Controls: Devices & Fullscreen -->
                        <div class="flex items-center gap-1.5">
                            <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-lg border border-slate-200" role="group" aria-label="Device Viewport Switcher">
                                <button type="button" 
                                        @click="previewDevice = 'desktop'"
                                        :class="previewDevice === 'desktop' ? 'bg-white text-indigo-600 font-bold shadow-2xs' : 'text-slate-600 hover:text-slate-900'"
                                        class="px-2 py-1 text-xs rounded transition-all flex items-center gap-1 focus:ring-2 focus:ring-indigo-500 focus:outline-hidden" 
                                        title="Desktop View"
                                        aria-label="Desktop preview viewport">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    <span class="hidden sm:inline">Desktop</span>
                                </button>
                                <button type="button" 
                                        @click="previewDevice = 'tablet'"
                                        :class="previewDevice === 'tablet' ? 'bg-white text-indigo-600 font-bold shadow-2xs' : 'text-slate-600 hover:text-slate-900'"
                                        class="px-2 py-1 text-xs rounded transition-all flex items-center gap-1 focus:ring-2 focus:ring-indigo-500 focus:outline-hidden" 
                                        title="Tablet View"
                                        aria-label="Tablet preview viewport">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                    <span class="hidden sm:inline">Tablet</span>
                                </button>
                                <button type="button" 
                                        @click="previewDevice = 'mobile'"
                                        :class="previewDevice === 'mobile' ? 'bg-white text-indigo-600 font-bold shadow-2xs' : 'text-slate-600 hover:text-slate-900'"
                                        class="px-2 py-1 text-xs rounded transition-all flex items-center gap-1 focus:ring-2 focus:ring-indigo-500 focus:outline-hidden" 
                                        title="Mobile View"
                                        aria-label="Mobile preview viewport">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                    <span class="hidden sm:inline">Mobile</span>
                                </button>
                            </div>

                            <!-- Fullscreen Toggle -->
                            <button type="button" 
                                    @click="isFullscreen = !isFullscreen" 
                                    class="btn btn-secondary p-1.5 text-xs text-slate-600 hover:text-slate-900" 
                                    :title="isFullscreen ? 'Exit Fullscreen' : 'Fullscreen Preview'"
                                    :aria-label="isFullscreen ? 'Exit fullscreen preview' : 'Open fullscreen preview'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 4l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Pixel-Perfect Isolated Iframe Container -->
                    <div class="bg-slate-900/5 p-2 sm:p-4 rounded-xl border border-slate-200 overflow-x-auto min-h-[650px]">
                        <div class="transition-all duration-300 shadow-2xl rounded-xl overflow-hidden border border-slate-800 bg-white"
                             :class="{
                                 'w-full': previewDevice === 'desktop',
                                 'w-[768px] mx-auto': previewDevice === 'tablet',
                                 'w-[375px] mx-auto': previewDevice === 'mobile',
                                 'h-[85vh]': isFullscreen
                             }">
                            <iframe x-ref="previewIframe"
                                    class="w-full h-[750px] border-0 rounded-xl"
                                    :class="{'h-full': isFullscreen}"
                                    title="Public Landing Page Live Preview">
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>

    <!-- Floating Sticky Unsaved Changes Bar -->
    <div x-show="isDirty" 
         x-transition.slide.bottom 
         class="fixed bottom-6 left-1/2 -translate-x-1/2 z-40 bg-slate-900 text-white px-5 py-3 rounded-2xl shadow-2xl flex items-center gap-4 border border-slate-800"
         style="display: none;">
        <div class="flex items-center gap-2 text-xs">
            <span class="w-2 h-2 rounded-full bg-amber-400 animate-ping"></span>
            <span class="font-semibold">Unsaved changes detected</span>
            <span class="text-slate-400 font-mono hidden sm:inline">(Press Ctrl+S to save)</span>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" @click="location.reload()" class="px-3 py-1 text-xs text-slate-300 hover:text-white font-medium">Discard</button>
            <button type="button" @click="document.getElementById('page-editor-form').requestSubmit()" class="btn btn-primary py-1.5 px-4 text-xs font-bold shadow-xs">Save Changes</button>
        </div>
    </div>

    <!-- Modals Partial Inclusions -->
    @include('admin.content.partials.modals.palette')
    @include('admin.content.partials.modals.media-picker')
    @include('admin.content.partials.modals.presets')
    @include('admin.content.partials.modals.confirm')
    @include('admin.content.partials.toast')

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
@include('admin.content.partials.editor-script')
@endpush