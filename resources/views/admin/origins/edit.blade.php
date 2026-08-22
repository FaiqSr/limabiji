@extends('admin.layouts.app')
@php $isCreating = !$origin->exists; @endphp
@section('title', $isCreating ? 'New Origin' : 'Edit ' . $origin->name)
@section('page_title', $isCreating ? 'Create Coffee Origin' : 'Edit Origin: ' . $origin->name)

@section('content')
<form action="{{ $isCreating ? route('admin.origins.store') : route('admin.origins.update', $origin) }}" 
      method="POST"
      id="origin-editor-form"
      x-data="{ isSubmitting: false }"
      @submit="isSubmitting = true"
      @keydown.window.ctrl.s.prevent="document.getElementById('origin-editor-form').requestSubmit()"
      @keydown.window.meta.s.prevent="document.getElementById('origin-editor-form').requestSubmit()">
    @csrf
    @if (!$isCreating)
        @method('PUT')
    @endif

    <!-- Sticky Top Action Bar -->
    <div class="sticky top-20 z-30 mb-6 bg-white/95 backdrop-blur-md border border-slate-200 rounded-2xl p-4 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3 min-w-0">
            <a href="{{ route('admin.origins.index') }}" class="btn btn-secondary text-xs py-2 px-3 shrink-0" title="Back to Origins list">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span class="hidden sm:inline">Back</span>
            </a>

            <div class="min-w-0">
                <div class="flex items-center gap-2">
                    <h3 class="text-sm font-bold text-slate-900 truncate">
                        {{ $isCreating ? 'Create New Coffee Origin' : 'Edit Origin: ' . $origin->name }}
                    </h3>
                    @if (!$isCreating)
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ $origin->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-200' }} shrink-0">
                            {{ $origin->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    @endif
                </div>
                <p class="text-[11px] text-slate-500 font-mono truncate">
                    {{ $origin->slug ? '/origin/' . $origin->slug : 'New origin region profile' }}
                </p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-2">
            @if (!$isCreating && $origin->slug)
                <a href="{{ route('landingpages.origins', $origin->slug) }}" 
                   target="_blank" 
                   class="btn btn-secondary text-xs py-2 px-3 hidden md:inline-flex items-center gap-1">
                    <span>View Live</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </a>
            @endif

            <button type="submit" 
                    :disabled="isSubmitting"
                    class="btn btn-primary text-xs py-2 px-4 shadow-2xs font-bold flex items-center gap-1.5 disabled:opacity-60 disabled:cursor-not-allowed">
                <template x-if="!isSubmitting">
                    <div class="inline-flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Save Origin</span>
                        <kbd class="hidden lg:inline-block text-[10px] font-mono opacity-60 bg-slate-800 px-1.5 py-0.5 rounded ml-1">Ctrl+S</kbd>
                    </div>
                </template>
                <template x-if="isSubmitting">
                    <div class="inline-flex items-center gap-1.5">
                        <svg class="w-4 h-4 animate-spin inline" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        <span>Saving Origin...</span>
                    </div>
                </template>
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
        <!-- Left Column: Primary & Regional Specs Cards -->
        <div class="space-y-6">
            <!-- Card 1: General & Location Information -->
            <div class="card-modern space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-base font-semibold text-slate-900">General & Location Details</h3>
                    <span class="text-xs text-slate-400">Basic identification</span>
                </div>

                <div>
                    <label for="name" class="block text-xs font-semibold text-slate-700 mb-1">
                        Region Name <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           value="{{ old('name', $origin->name) }}" 
                           required 
                           placeholder="e.g. Toraja, Gayo, Flores Bajawa" 
                           class="w-full text-xs bg-white border @error('name') !border-rose-500 focus:!ring-rose-500 @else border-slate-200 @enderror rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500">
                    @error('name')
                        <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="slug" class="block text-xs font-semibold text-slate-700 mb-1">
                        URL Slug <span class="text-rose-500">*</span>
                    </label>
                    <div class="flex items-center">
                        <span class="inline-flex items-center px-3 py-2.5 rounded-l-lg border border-r-0 border-slate-200 bg-slate-50 text-slate-500 text-xs font-mono">/origin/</span>
                        <input type="text" 
                               name="slug" 
                               id="slug" 
                               value="{{ old('slug', $origin->slug) }}" 
                               required 
                               placeholder="toraja" 
                               class="w-full text-xs bg-white border @error('slug') !border-rose-500 focus:!ring-rose-500 @else border-slate-200 @enderror rounded-r-lg p-2.5 focus:ring-2 focus:ring-indigo-500 font-mono">
                    </div>
                    @error('slug')
                        <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="province" class="block text-xs font-semibold text-slate-700 mb-1">
                        Province <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           name="province" 
                           id="province" 
                           value="{{ old('province', $origin->province) }}" 
                           required 
                           placeholder="e.g. South Sulawesi, Aceh, East Nusa Tenggara" 
                           class="w-full text-xs bg-white border @error('province') !border-rose-500 focus:!ring-rose-500 @else border-slate-200 @enderror rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500">
                    @error('province')
                        <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <x-admin.image-uploader
                        name="image"
                        :value="$origin->image"
                        context="origins"
                        label="Featured Cover Image" />
                    @error('image')
                        <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4 border-t border-slate-100">
                    <x-admin.gallery-uploader
                        name="gallery"
                        :value="$origin->gallery"
                        context="origins"
                        label="Origin Gallery Photos" />
                    @error('gallery')
                        <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Card 2: Agronomic & Specialty Coffee Specs -->
            <div class="card-modern space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-base font-semibold text-slate-900">Agronomic & Cupping Specs</h3>
                    <span class="text-xs text-slate-400">Technical parameters</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="altitude" class="block text-xs font-semibold text-slate-700 mb-1">
                            Altitude Range <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" 
                               name="altitude" 
                               id="altitude" 
                               value="{{ old('altitude', $origin->altitude) }}" 
                               required 
                               placeholder="e.g. 1400–1900m" 
                               class="w-full text-xs bg-white border @error('altitude') !border-rose-500 focus:!ring-rose-500 @else border-slate-200 @enderror rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 font-mono">
                        @error('altitude')
                            <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="score" class="block text-xs font-semibold text-slate-700 mb-1">
                            Cupping Score <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" 
                               name="score" 
                               id="score" 
                               value="{{ old('score', $origin->score) }}" 
                               required 
                               placeholder="e.g. 86.5 or 82+" 
                               class="w-full text-xs bg-white border @error('score') !border-rose-500 focus:!ring-rose-500 @else border-slate-200 @enderror rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 font-mono">
                        @error('score')
                            <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="process" class="block text-xs font-semibold text-slate-700 mb-1">
                            Processing Method <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" 
                               name="process" 
                               id="process" 
                               value="{{ old('process', $origin->process) }}" 
                               required 
                               placeholder="e.g. Washed, Enzymatic Honey" 
                               class="w-full text-xs bg-white border @error('process') !border-rose-500 focus:!ring-rose-500 @else border-slate-200 @enderror rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500">
                        @error('process')
                            <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="harvest" class="block text-xs font-semibold text-slate-700 mb-1">
                            Harvest Period <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" 
                               name="harvest" 
                               id="harvest" 
                               value="{{ old('harvest', $origin->harvest) }}" 
                               required 
                               placeholder="e.g. May – Nov" 
                               class="w-full text-xs bg-white border @error('harvest') !border-rose-500 focus:!ring-rose-500 @else border-slate-200 @enderror rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500">
                        @error('harvest')
                            <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="varietals" class="block text-xs font-semibold text-slate-700 mb-1">
                        Coffee Varietals <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           name="varietals" 
                           id="varietals" 
                           value="{{ old('varietals', $origin->varietals) }}" 
                           required 
                           placeholder="e.g. S795, Typica, Lini S, Kartika" 
                           class="w-full text-xs bg-white border @error('varietals') !border-rose-500 focus:!ring-rose-500 @else border-slate-200 @enderror rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500">
                    @error('varietals')
                        <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Right Column: Bilingual Overview, Repeaters & Publishing Cards -->
        <div class="space-y-6">
            <!-- Bilingual Regional Overview Component with WAI-ARIA Semantics -->
            <x-form.locale-tabs title="Regional Overview & Narrative" tab-id="origin-overview">
                <x-slot:en>
                    <div>
                        <label for="overview" class="block text-xs font-semibold text-slate-700 mb-1">Overview Description (English)</label>
                        <textarea name="overview" 
                                  id="overview" 
                                  rows="5" 
                                  placeholder="Describe the region's climate, terroir, soil composition, and specialty cup characteristics..." 
                                  class="w-full text-xs bg-white border @error('overview') !border-rose-500 focus:!ring-rose-500 @else border-slate-200 @enderror rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 leading-relaxed">{{ old('overview', $origin->overview) }}</textarea>
                        @error('overview')
                            <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </x-slot:en>

                <x-slot:id>
                    <div>
                        <label for="overview_id" class="block text-xs font-semibold text-slate-700 mb-1">Deskripsi Wilayah (Bahasa Indonesia)</label>
                        <textarea name="overview_id" 
                                  id="overview_id" 
                                  rows="5" 
                                  placeholder="Deskripsikan iklim daerah, tanah, sejarah perkebunan, dan profil rasa kopi khas daerah ini..." 
                                  class="w-full text-xs bg-white border @error('overview_id') !border-rose-500 focus:!ring-rose-500 @else border-slate-200 @enderror rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 leading-relaxed">{{ old('overview_id', $origin->overview_id) }}</textarea>
                        @error('overview_id')
                            <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </x-slot:id>
            </x-form.locale-tabs>

            <!-- Reusable Flavor Profile Notes Repeater -->
            <x-form.repeater 
                name="flavor"
                :items="$origin->flavor"
                label="Flavor Profile Notes"
                placeholder="e.g. Floral Jasmine, Bergamot, Dark Chocolate, Brown Sugar"
                button-label="Add Flavor Note" />

            <!-- Publishing & Display Settings Card -->
            <div class="card-modern space-y-4">
                <h3 class="text-base font-semibold text-slate-900 pb-2 border-b border-slate-100">Visibility & Display Settings</h3>
                <div class="space-y-4">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" 
                               name="is_active" 
                               value="1" 
                               {{ old('is_active', $origin->is_active ?? true) ? 'checked' : '' }} 
                               class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300">
                        <div>
                            <span class="text-xs font-semibold text-slate-800">Active Origin</span>
                            <p class="text-[11px] text-slate-500">Display this origin profile on public landing pages and origins slider.</p>
                        </div>
                    </label>

                    <div>
                        <label for="order" class="block text-xs font-semibold text-slate-700 mb-1">Display Index Order</label>
                        <input type="number" 
                               name="order" 
                               id="order" 
                               value="{{ old('order', $origin->order ?: 0) }}" 
                               min="0" 
                               class="w-full text-xs bg-white border @error('order') !border-rose-500 focus:!ring-rose-500 @else border-slate-200 @enderror rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 font-mono">
                        @error('order')
                            <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- <div class="pt-2">
                        <button type="submit" 
                                :disabled="isSubmitting"
                                class="btn btn-primary w-full py-2.5 text-xs font-bold shadow-2xs disabled:opacity-60 disabled:cursor-not-allowed">
                            <span x-show="!isSubmitting">Save Origin Changes</span>
                            <span x-show="isSubmitting" class="inline-flex items-center gap-2">
                                <svg class="w-4 h-4 animate-spin inline" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                </svg>
                                Saving...
                            </span>
                        </button>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</form>
@endsection