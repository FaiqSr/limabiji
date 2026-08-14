@extends('admin.layouts.app')
@php $isCreating = !$category->exists; @endphp
@section('title', $isCreating ? 'New Category' : 'Edit ' . $category->name)
@section('page_title', $isCreating ? 'Create News Category' : 'Edit Category: ' . $category->name)

@section('content')
<form action="{{ $isCreating ? route('admin.categories.store') : route('admin.categories.update', $category) }}" 
      method="POST"
      id="category-editor-form"
      x-data="{ 
          isSubmitting: false,
          name: @js(old('name', $category->name ?? '')),
          slug: @js(old('slug', $category->slug ?? '')),
          slugManuallyChanged: {{ $category->exists ? 'true' : 'false' }},
          generateSlug() {
              if (!this.slugManuallyChanged) {
                  this.slug = this.name.toLowerCase()
                      .replace(/[^\w ]+/g, '')
                      .replace(/ +/g, '-');
              }
          }
      }"
      @submit="isSubmitting = true"
      @keydown.window.ctrl.s.prevent="document.getElementById('category-editor-form').requestSubmit()"
      @keydown.window.meta.s.prevent="document.getElementById('category-editor-form').requestSubmit()">
    @csrf
    @if (!$isCreating)
        @method('PUT')
    @endif

    <!-- Sticky Top Action Bar -->
    <div class="sticky top-20 z-30 mb-6 bg-white/95 backdrop-blur-md border border-slate-200 rounded-2xl p-4 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3 min-w-0">
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary text-xs py-2 px-3 shrink-0" title="Back to Categories list">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span class="hidden sm:inline">Back</span>
            </a>

            <div class="min-w-0">
                <div class="flex items-center gap-2">
                    <h3 class="text-sm font-bold text-slate-900 truncate">
                        {{ $isCreating ? 'Create New News Category' : 'Edit Category: ' . $category->name }}
                    </h3>
                </div>
                <p class="text-[11px] text-slate-500 font-mono truncate">
                    {{ $category->slug ? 'Slug: ' . $category->slug : 'New category definition' }}
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <button type="submit" 
                    :disabled="isSubmitting"
                    class="btn btn-primary text-xs py-2 px-4 shadow-2xs font-bold flex items-center gap-1.5 disabled:opacity-60 disabled:cursor-not-allowed w-full sm:w-auto">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span x-text="isSubmitting ? 'Saving...' : '{{ $isCreating ? 'Create Category' : 'Save Changes' }}'"></span>
            </button>
        </div>
    </div>

    <!-- Main Form Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left: Category Info -->
        <div class="lg:col-span-2 space-y-6">
            <div class="card-modern space-y-5">
                <h4 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">Category Information</h4>

                <!-- Name (EN) -->
                <div>
                    <label for="name" class="block text-xs font-semibold text-slate-700 mb-1">
                        Category Name (English) <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           x-model="name"
                           @input="generateSlug()"
                           value="{{ old('name', $category->name) }}" 
                           required 
                           placeholder="e.g. Export Market, Innovation, Agritech" 
                           class="w-full text-xs bg-white border @error('name') border-rose-500 @else border-slate-200 @enderror rounded-xl p-2.5 focus:ring-2 focus:ring-indigo-500">
                    @error('name')
                        <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Name (ID) -->
                <div>
                    <label for="name_id" class="block text-xs font-semibold text-slate-700 mb-1 flex items-center gap-1.5">
                        <span>Category Name (Indonesian / ID)</span>
                        <span class="text-[10px] font-mono px-1.5 py-0.2 rounded bg-slate-100 text-slate-500">Optional</span>
                    </label>
                    <input type="text" 
                           name="name_id" 
                           id="name_id" 
                           value="{{ old('name_id', $category->name_id) }}" 
                           placeholder="e.g. Pasar Ekspor, Inovasi, Agriteknologi" 
                           class="w-full text-xs bg-white border @error('name_id') border-rose-500 @else border-slate-200 @enderror rounded-xl p-2.5 focus:ring-2 focus:ring-indigo-500">
                    @error('name_id')
                        <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Slug -->
                <div>
                    <label for="slug" class="block text-xs font-semibold text-slate-700 mb-1">
                        URL Slug <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="text" 
                               name="slug" 
                               id="slug" 
                               x-model="slug"
                               @input="slugManuallyChanged = true"
                               value="{{ old('slug', $category->slug) }}" 
                               required 
                               placeholder="export-market" 
                               class="w-full text-xs bg-white font-mono border @error('slug') border-rose-500 @else border-slate-200 @enderror rounded-xl p-2.5 focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1">Unique identifier used for URL filtering and routing.</p>
                    @error('slug')
                        <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Right: Help & Stats Sidebar -->
        <div class="space-y-6">
            @if (!$isCreating)
                <div class="card-modern space-y-3">
                    <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider font-mono">Linked Articles</h4>
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex items-center justify-between">
                        <span class="text-xs text-slate-600">Total Articles</span>
                        <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-md">
                            {{ $category->articles()->count() }}
                        </span>
                    </div>
                </div>
            @endif

            <div class="card-modern space-y-2.5 bg-slate-50/50">
                <h4 class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Category Guide</span>
                </h4>
                <p class="text-[11px] text-slate-600 leading-relaxed">
                    Categories help group news articles on the landing page. Articles can belong to multiple categories simultaneously.
                </p>
            </div>
        </div>
    </div>
</form>
@endsection
