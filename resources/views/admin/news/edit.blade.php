@extends('admin.layouts.app')
@section('title', isset($article) && $article->exists ? 'Edit Article' : 'New Article')
@section('page_title', isset($article) && $article->exists ? 'Edit Article: ' . $article->title : 'Create New Article')

@section('content')
<form action="{{ isset($article) && $article->exists ? route('admin.news.update', $article) : route('admin.news.store') }}" method="POST" x-data="{ locale: 'en' }">
    @csrf
    @if (isset($article) && $article->exists)
        @method('PUT')
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Form Left -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Article Basic Metadata Card -->
            <div class="card-modern">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                    <h3 class="text-base font-semibold text-slate-900">General Information</h3>
                    <a href="{{ route('admin.news.index') }}" class="text-xs text-slate-500 hover:text-indigo-600 font-medium flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to Articles
                    </a>
                </div>

                <div class="space-y-4">
                    <div>
                        <label for="slug" class="block text-xs font-semibold text-slate-700 mb-1">URL Slug <span class="text-rose-500">*</span></label>
                        <div class="flex items-center">
                            <span class="inline-flex items-center px-3 py-2 rounded-l-lg border border-r-0 border-slate-200 bg-slate-50 text-slate-500 text-xs font-mono">/news/</span>
                            <input type="text" name="slug" id="slug" value="{{ old('slug', $article->slug ?? '') }}" required placeholder="lima-biji-wins-award" class="w-full text-xs bg-white border @error('slug') border-rose-500 @else border-slate-200 @enderror rounded-r-lg p-2.5 focus:ring-2 focus:ring-indigo-500 font-mono">
                        </div>
                        @error('slug')
                            <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="category" class="block text-xs font-semibold text-slate-700 mb-1">Category <span class="text-rose-500">*</span></label>
                            <input type="text" name="category" id="category" value="{{ old('category', $article->category ?? 'Blog') }}" required placeholder="Export Market / Innovation" class="w-full text-xs bg-white border @error('category') border-rose-500 @else border-slate-200 @enderror rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500">
                            @error('category')
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
                    <h3 class="text-base font-semibold text-slate-900">Bilingual Content & Translations</h3>
                    <!-- Language Tabs -->
                    <div class="inline-flex bg-slate-100 p-0.5 rounded-lg border border-slate-200">
                        <button type="button" @click="locale = 'en'" :class="locale === 'en' ? 'bg-white text-indigo-600 shadow-2xs font-semibold' : 'text-slate-600 hover:text-slate-900'" class="px-3 py-1 text-xs rounded-md transition-all">
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
                        <input type="text" name="title" id="title" value="{{ old('title', $article->title ?? '') }}" required placeholder="e.g. Lima Biji Wins International Specialty Coffee Award" class="w-full text-xs bg-white border @error('title') border-rose-500 @else border-slate-200 @enderror rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500">
                        @error('title')
                            <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="excerpt" class="block text-xs font-semibold text-slate-700 mb-1">Excerpt Summary (English)</label>
                        <textarea name="excerpt" id="excerpt" rows="2" placeholder="Short summary for search results & cards..." class="w-full text-xs bg-white border @error('excerpt') border-rose-500 @else border-slate-200 @enderror rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500">{{ old('excerpt', $article->excerpt ?? '') }}</textarea>
                        @error('excerpt')
                            <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="content" class="block text-xs font-semibold text-slate-700 mb-1">Full Article Body (English)</label>
                        <input type="hidden" name="content" id="content-input" value="{{ old('content', $article->content ?? '') }}">
                        <trix-editor input="content-input" class="trix-content mt-1 bg-white border @error('content') border-rose-500 @else border-slate-200 @enderror rounded-lg p-3 min-h-[220px] text-xs leading-relaxed focus:ring-2 focus:ring-indigo-500" data-placeholder="Write full article body text..."></trix-editor>
                        @error('content')
                            <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Indonesian Content Tab -->
                <div x-show="locale === 'id'" class="space-y-4" style="display: none;">
                    <div>
                        <label for="title_id" class="block text-xs font-semibold text-slate-700 mb-1">Article Title (Bahasa Indonesia)</label>
                        <input type="text" name="title_id" id="title_id" value="{{ old('title_id', $article->title_id ?? '') }}" placeholder="contoh: Lima Biji Memenangkan Penghargaan Kopi Specialty Internasional" class="w-full text-xs bg-white border @error('title_id') border-rose-500 @else border-slate-200 @enderror rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500">
                        @error('title_id')
                            <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="excerpt_id" class="block text-xs font-semibold text-slate-700 mb-1">Excerpt Summary (Bahasa Indonesia)</label>
                        <textarea name="excerpt_id" id="excerpt_id" rows="2" placeholder="Ringkasan singkat untuk hasil pencarian & kartu artikel..." class="w-full text-xs bg-white border @error('excerpt_id') border-rose-500 @else border-slate-200 @enderror rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500">{{ old('excerpt_id', $article->excerpt_id ?? '') }}</textarea>
                        @error('excerpt_id')
                            <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="content_id" class="block text-xs font-semibold text-slate-700 mb-1">Full Article Body (Bahasa Indonesia)</label>
                        <input type="hidden" name="content_id" id="content_id-input" value="{{ old('content_id', $article->content_id ?? '') }}">
                        <trix-editor input="content_id-input" class="trix-content mt-1 bg-white border @error('content_id') border-rose-500 @else border-slate-200 @enderror rounded-lg p-3 min-h-[220px] text-xs leading-relaxed focus:ring-2 focus:ring-indigo-500" data-placeholder="Tuliskan isi artikel lengkap dalam Bahasa Indonesia..."></trix-editor>
                        @error('content_id')
                            <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Right -->
        <div class="space-y-6">
            <div class="card-modern">
                <h3 class="text-base font-semibold text-slate-900 mb-4 pb-2 border-b border-slate-100">Publishing Options</h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="status" class="block text-xs font-semibold text-slate-700 mb-1">Publication Status</label>
                        <select name="status" id="status" class="w-full text-xs bg-white border @error('status') border-rose-500 @else border-slate-200 @enderror rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500">
                            <option value="draft" {{ (old('status', $article->status ?? 'draft') === 'draft') ? 'selected' : '' }}>Draft</option>
                            <option value="pending" {{ (old('status', $article->status ?? 'draft') === 'pending') ? 'selected' : '' }}>Submit for Review (Pending)</option>
                            @if (auth()->user()?->role === 'admin')
                                <option value="published" {{ (old('status', $article->status ?? 'draft') === 'published') ? 'selected' : '' }}>Published</option>
                            @endif
                        </select>
                        @error('status')
                            <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="published_at" class="block text-xs font-semibold text-slate-700 mb-1">Publication Date</label>
                        <input type="datetime-local" name="published_at" id="published_at" value="{{ old('published_at', isset($article) && $article->published_at ? $article->published_at->format('Y-m-d\TH:i') : '') }}" class="w-full text-xs bg-white border @error('published_at') border-rose-500 @else border-slate-200 @enderror rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500">
                        @error('published_at')
                            <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-full py-2.5 text-xs font-semibold shadow-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Save Article
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection