@extends('admin.layouts.app')
@section('title', isset($article) && $article->exists ? 'Edit Article' : 'New Article')
@section('page_title', isset($article) && $article->exists ? 'Edit Article: ' . $article->title : 'Create New Article')

@section('content')
<form action="{{ isset($article) && $article->exists ? route('admin.news.update', $article) : route('admin.news.store') }}" 
      method="POST" 
      id="article-editor-form"
      x-data="{ locale: 'en' }"
      @keydown.window.ctrl.s.prevent="document.getElementById('article-editor-form').requestSubmit()"
      @keydown.window.meta.s.prevent="document.getElementById('article-editor-form').requestSubmit()">
    @csrf
    @if (isset($article) && $article->exists)
        @method('PUT')
    @endif

    <!-- Sticky Top Action Bar -->
    <div class="mb-6 bg-white/95 backdrop-blur-md border border-slate-200 rounded-2xl p-4 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3 min-w-0">
            <a href="{{ route('admin.news.index') }}" class="btn btn-secondary text-xs py-2 px-3 shrink-0" title="Back to Articles list">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span class="hidden sm:inline">Back</span>
            </a>

            <div class="min-w-0">
                <div class="flex items-center gap-2">
                    <h3 class="text-sm font-bold text-slate-900 truncate">
                        {{ isset($article) && $article->exists ? ($article->title ?: 'Edit Article') : 'Create New Article' }}
                    </h3>
                    @if (isset($article) && $article->exists)
                        <span class="badge badge-{{ $article->status }} text-[11px] shrink-0">
                            {{ ucfirst($article->status) }}
                        </span>
                    @endif
                </div>
                <p class="text-[11px] text-slate-500 font-mono truncate">
                    {{ isset($article) && $article->slug ? '/news/' . $article->slug : 'New news publication' }}
                </p>
            </div>
        </div>

        <!-- Sticky Controls -->
        <div class="flex items-center gap-2.5 shrink-0 self-end sm:self-auto">
            <!-- Language Switcher in Sticky Header -->
            <div class="inline-flex bg-slate-100 p-0.5 rounded-xl border border-slate-200">
                <button type="button" 
                        @click="locale = 'en'" 
                        :class="locale === 'en' ? 'bg-white text-emerald-600 shadow-2xs font-semibold' : 'text-slate-600 hover:text-slate-900'" 
                        class="px-2.5 py-1.5 text-xs rounded-lg transition-all flex items-center gap-1">
                    <span>🇬🇧 EN</span>
                </button>
                <button type="button" 
                        @click="locale = 'id'" 
                        :class="locale === 'id' ? 'bg-white text-emerald-600 shadow-2xs font-semibold' : 'text-slate-600 hover:text-slate-900'" 
                        class="px-2.5 py-1.5 text-xs rounded-lg transition-all flex items-center gap-1">
                    <span>🇮🇩 ID</span>
                </button>
            </div>

            @if (isset($article) && $article->exists && $article->status === 'published')
                <a href="{{ route('landingpages.news.detail', $article->slug) }}" 
                   target="_blank" 
                   class="btn btn-secondary text-xs py-2 px-3 hidden md:inline-flex items-center gap-1">
                    <span>Preview</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </a>
            @endif

            <!-- Sticky Save Button -->
            <button type="submit" class="btn btn-primary text-xs py-2 px-4 shadow-2xs font-bold flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>Save</span>
                <kbd class="hidden lg:inline-block text-[10px] font-mono opacity-60 bg-slate-800 px-1.5 py-0.5 rounded ml-1">Ctrl+S</kbd>
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        <!-- Main Form Left -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Article Basic Metadata Card -->
            <div class="card-modern">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                    <h3 class="text-base font-semibold text-slate-900">General Information</h3>
                    <span class="text-xs text-slate-400">Basic article attributes</span>
                </div>

                <div class="space-y-4">
                    <div>
                        <label for="slug" class="block text-xs font-semibold text-slate-700 mb-1">URL Slug <span class="text-rose-500">*</span></label>
                        <div class="flex items-center">
                            <span class="inline-flex items-center px-3 py-2 rounded-l-lg border border-r-0 border-slate-200 bg-slate-50 text-slate-500 text-xs font-mono">/news/</span>
                            <input type="text" name="slug" id="slug" value="{{ old('slug', $article->slug ?? '') }}" required placeholder="lima-biji-wins-award" class="w-full text-xs bg-white border @error('slug') border-rose-500 @else border-slate-200 @enderror rounded-r-lg p-2.5 focus:ring-2 focus:ring-emerald-500 font-mono">
                        </div>
                        @error('slug')
                            <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="grid grid-cols-1 gap-4 items-start">
                        <!-- Multi-Select Category Component -->
                        @php
                            $selectedCategoryIds = old('category_ids', $article->categories ? $article->categories->pluck('id')->toArray() : []);
                            $allCategoriesJson = ($categories ?? collect())->map(fn($cat) => [
                                'id' => $cat->id,
                                'name' => $cat->name,
                                'name_id' => $cat->name_id,
                                'slug' => $cat->slug,
                            ])->values();
                        @endphp
                        <div x-data="{
                            open: false,
                            search: '',
                            selected: @js($selectedCategoryIds),
                            categories: @js($allCategoriesJson),
                            get filteredCategories() {
                                if (!this.search) return this.categories;
                                const q = this.search.toLowerCase();
                                return this.categories.filter(c => 
                                    c.name.toLowerCase().includes(q) || 
                                    (c.name_id && c.name_id.toLowerCase().includes(q)) ||
                                    c.slug.toLowerCase().includes(q)
                                );
                            },
                            toggle(id) {
                                if (this.selected.includes(id)) {
                                    this.selected = this.selected.filter(item => item !== id);
                                } else {
                                    this.selected.push(id);
                                }
                            },
                            isSelected(id) {
                                return this.selected.includes(id);
                            },
                            getCategoryName(id) {
                                const cat = this.categories.find(c => c.id === id);
                                return cat ? cat.name : '';
                            }
                        }" class="relative">
                            <div class="flex items-center justify-between mb-1">
                                <label class="block text-xs font-semibold text-slate-700">
                                    Categories <span class="text-slate-400 font-normal">(Multiple allowed)</span>
                                </label>
                                <a href="{{ route('admin.categories.create') }}" target="_blank" class="text-[11px] text-emerald-600 hover:text-emerald-800 font-semibold inline-flex items-center gap-0.5">
                                    <span>+ Add Category</span>
                                </a>
                            </div>

                            <!-- Hidden input array for form submission -->
                            <template x-for="id in selected" :key="id">
                                <input type="hidden" name="category_ids[]" :value="id">
                            </template>

                            <!-- Multi-select trigger & selected chips -->
                            <div @click="open = !open" 
                                 @click.outside="open = false"
                                 class="min-h-[42px] w-full text-xs bg-white border @error('category_ids') border-rose-500 @else border-slate-200 @enderror hover:border-slate-300 rounded-xl p-1.5 cursor-pointer flex flex-wrap items-center gap-1.5 transition-all focus-within:ring-2 focus-within:ring-emerald-500">
                                
                                <template x-if="selected.length === 0">
                                    <span class="text-slate-400 px-2 py-1 select-none">Click to select categories...</span>
                                </template>

                                <template x-for="id in selected" :key="id">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60 shadow-2xs">
                                        <span x-text="getCategoryName(id)"></span>
                                        <button type="button" 
                                                @click.stop="toggle(id)" 
                                                class="text-emerald-400 hover:text-emerald-700 p-0.5 rounded hover:bg-emerald-100/50">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </span>
                                </template>

                                <div class="ml-auto pr-1.5 flex items-center text-slate-400 pointer-events-none">
                                    <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>

                            <!-- Dropdown List -->
                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute z-50 mt-1 w-full bg-white border border-slate-200 rounded-xl shadow-lg p-2 max-h-60 overflow-y-auto space-y-1"
                                 style="display: none;">
                                
                                <div class="p-1 border-b border-slate-100 mb-1">
                                    <input type="text" 
                                           x-model="search" 
                                           @click.stop 
                                           placeholder="Filter categories..." 
                                           class="w-full text-xs bg-slate-50 border border-slate-200 rounded-lg p-1.5 focus:ring-1 focus:ring-emerald-500">
                                </div>

                                <template x-for="cat in filteredCategories" :key="cat.id">
                                    <div @click.stop="toggle(cat.id)"
                                         class="flex items-center justify-between px-2.5 py-1.5 rounded-lg text-xs cursor-pointer hover:bg-slate-50 transition-colors"
                                         :class="isSelected(cat.id) ? 'bg-emerald-50/60 font-semibold text-emerald-900' : 'text-slate-700'">
                                        <div class="flex items-center gap-2">
                                            <input type="checkbox" 
                                                   :checked="isSelected(cat.id)" 
                                                   @click.stop="toggle(cat.id)" 
                                                   class="rounded text-emerald-600 focus:ring-emerald-500 border-slate-300 pointer-events-none">
                                            <span x-text="cat.name"></span>
                                            <template x-if="cat.name_id">
                                                <span class="text-[10px] text-slate-400 font-normal" x-text="'(' + cat.name_id + ')'"></span>
                                            </template>
                                        </div>
                                        <span class="text-[10px] font-mono text-slate-400" x-text="cat.slug"></span>
                                    </div>
                                </template>

                                <template x-if="filteredCategories.length === 0">
                                    <div class="p-3 text-center text-xs text-slate-400">
                                        No matching categories.
                                    </div>
                                </template>
                            </div>

                            @error('category_ids')
                                <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <x-admin.image-uploader
                                name="image"
                                :value="$article->image ?? ''"
                                context="articles"
                                label="Featured Image" />
                            @error('image')
                                <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bilingual Content Tabs Card -->
            <div class="card-modern">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                    <h3 class="text-base font-semibold text-slate-900">Bilingual Content & Body</h3>
                    <!-- Language Tabs -->
                    <div class="inline-flex bg-slate-100 p-0.5 rounded-lg border border-slate-200">
                        <button type="button" @click="locale = 'en'" :class="locale === 'en' ? 'bg-white text-emerald-600 shadow-2xs font-semibold' : 'text-slate-600 hover:text-slate-900'" class="px-3 py-1 text-xs rounded-md transition-all">
                            🇬🇧 English (EN)
                        </button>
                        <button type="button" @click="locale = 'id'" :class="locale === 'id' ? 'bg-white text-emerald-600 shadow-2xs font-semibold' : 'text-slate-600 hover:text-slate-900'" class="px-3 py-1 text-xs rounded-md transition-all">
                            🇮🇩 Bahasa (ID)
                        </button>
                    </div>
                </div>

                <!-- English Content Tab -->
                <div x-show="locale === 'en'" class="space-y-4">
                    <div>
                        <label for="title" class="block text-xs font-semibold text-slate-700 mb-1">Article Title (English) <span class="text-rose-500">*</span></label>
                        <input type="text" name="title" id="title" value="{{ old('title', $article->title ?? '') }}" required placeholder="e.g. Lima Biji Wins International Specialty Coffee Award" class="w-full text-xs bg-white border @error('title') border-rose-500 @else border-slate-200 @enderror rounded-lg p-2.5 focus:ring-2 focus:ring-emerald-500">
                        @error('title')
                            <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="excerpt" class="block text-xs font-semibold text-slate-700 mb-1">Excerpt Summary (English)</label>
                        <textarea name="excerpt" id="excerpt" rows="2" placeholder="Short summary for search results & cards..." class="w-full text-xs bg-white border @error('excerpt') border-rose-500 @else border-slate-200 @enderror rounded-lg p-2.5 focus:ring-2 focus:ring-emerald-500">{{ old('excerpt', $article->excerpt ?? '') }}</textarea>
                        @error('excerpt')
                            <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="content" class="block text-xs font-semibold text-slate-700 mb-1">Full Article Body (English)</label>
                        <input type="hidden" name="content" id="content-input" value="{{ old('content', $article->content ?? '') }}">
                        <trix-editor input="content-input" class="trix-content mt-1 bg-white border @error('content') border-rose-500 @else border-slate-200 @enderror rounded-lg p-3 min-h-[220px] text-xs leading-relaxed focus:ring-2 focus:ring-emerald-500" data-placeholder="Write full article body text..."></trix-editor>
                        @error('content')
                            <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Indonesian Content Tab -->
                <div x-show="locale === 'id'" class="space-y-4" style="display: none;">
                    <div>
                        <label for="title_id" class="block text-xs font-semibold text-slate-700 mb-1">Article Title (Bahasa Indonesia)</label>
                        <input type="text" name="title_id" id="title_id" value="{{ old('title_id', $article->title_id ?? '') }}" placeholder="contoh: Lima Biji Memenangkan Penghargaan Kopi Specialty Internasional" class="w-full text-xs bg-white border @error('title_id') border-rose-500 @else border-slate-200 @enderror rounded-lg p-2.5 focus:ring-2 focus:ring-emerald-500">
                        @error('title_id')
                            <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="excerpt_id" class="block text-xs font-semibold text-slate-700 mb-1">Excerpt Summary (Bahasa Indonesia)</label>
                        <textarea name="excerpt_id" id="excerpt_id" rows="2" placeholder="Ringkasan singkat untuk hasil pencarian & kartu artikel..." class="w-full text-xs bg-white border @error('excerpt_id') border-rose-500 @else border-slate-200 @enderror rounded-lg p-2.5 focus:ring-2 focus:ring-emerald-500">{{ old('excerpt_id', $article->excerpt_id ?? '') }}</textarea>
                        @error('excerpt_id')
                            <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="content_id" class="block text-xs font-semibold text-slate-700 mb-1">Full Article Body (Bahasa Indonesia)</label>
                        <input type="hidden" name="content_id" id="content_id-input" value="{{ old('content_id', $article->content_id ?? '') }}">
                        <trix-editor input="content_id-input" class="trix-content mt-1 bg-white border @error('content_id') border-rose-500 @else border-slate-200 @enderror rounded-lg p-3 min-h-[220px] text-xs leading-relaxed focus:ring-2 focus:ring-emerald-500" data-placeholder="Tuliskan isi artikel lengkap dalam Bahasa Indonesia..."></trix-editor>
                        @error('content_id')
                            <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Sticky Sidebar Right -->
        <div class="lg:col-span-1">
            <div class="sticky top-32 space-y-6">
                <!-- Publishing Options Card -->
                <div class="card-modern space-y-4">
                    <h3 class="text-base font-semibold text-slate-900 pb-2 border-b border-slate-100">Publishing Options</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="status" class="block text-xs font-semibold text-slate-700 mb-1">Publication Status</label>
                            <select name="status" id="status" class="w-full text-xs bg-white border @error('status') border-rose-500 @else border-slate-200 @enderror rounded-lg p-2.5 focus:ring-2 focus:ring-emerald-500 font-semibold">
                                <option value="draft" {{ (old('status', $article->status ?? 'draft') === 'draft') ? 'selected' : '' }}>Draft (Private)</option>
                                <option value="published" {{ (old('status', $article->status ?? 'draft') === 'published') ? 'selected' : '' }}>Published (Live)</option>
                            </select>
                            @error('status')
                                <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="published_at" class="block text-xs font-semibold text-slate-700 mb-1">Publication Schedule Date</label>
                            <input type="datetime-local" name="published_at" id="published_at" value="{{ old('published_at', isset($article) && $article->published_at ? $article->published_at->format('Y-m-d\TH:i') : '') }}" class="w-full text-xs bg-white border @error('published_at') border-rose-500 @else border-slate-200 @enderror rounded-lg p-2.5 focus:ring-2 focus:ring-emerald-500 font-mono">
                            @error('published_at')
                                <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                </div>

                @if (isset($article) && $article->exists)
                    <!-- Quick Stats & Metadata Card -->
                    <div class="card-modern text-xs text-slate-500 space-y-2.5">
                        <h4 class="font-semibold text-slate-800 pb-1.5 border-b border-slate-100">Article Info</h4>
                        <div class="flex justify-between items-center">
                            <span>Author:</span>
                            <span class="font-semibold text-slate-700">{{ $article->author?->name ?? 'Admin' }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span>Views:</span>
                            <span class="font-mono font-semibold text-slate-700">{{ number_format($article->views) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span>Created:</span>
                            <span class="font-mono text-slate-600">{{ $article->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span>Updated:</span>
                            <span class="font-mono text-slate-600">{{ $article->updated_at->format('d M Y, H:i') }}</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</form>
@endsection