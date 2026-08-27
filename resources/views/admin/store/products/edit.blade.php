@extends('admin.layouts.app')
@section('title', 'Edit Product: ' . $product->name)
@section('page_title', 'Edit Store Product')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Sticky Top Action Bar -->
    <div class="mb-6 bg-white/95 backdrop-blur-md border border-slate-200 rounded-2xl p-4 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3 min-w-0">
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary text-xs py-2 px-3 shrink-0" title="Back to Products list">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span class="hidden sm:inline">Back</span>
            </a>

            <div class="min-w-0">
                <div class="flex items-center gap-2">
                    <h3 class="text-sm font-bold text-slate-900 truncate">
                        {{ $product->name }}
                    </h3>
                    <span class="text-[10px] font-bold font-mono px-2 py-0.5 rounded bg-slate-100 text-slate-700 border border-slate-200 uppercase shrink-0">
                        {{ $product->category }}
                    </span>
                    @if ($product->is_featured)
                        <span class="px-1.5 py-0.2 text-[9px] font-bold rounded bg-amber-50 text-amber-700 border border-amber-200 font-mono uppercase shrink-0">
                            Featured
                        </span>
                    @endif
                </div>
                <p class="text-[11px] text-slate-500 font-mono truncate">
                    /store/{{ $product->slug }}
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2 shrink-0">
            <a href="{{ url('store/' . $product->slug) }}" target="_blank" class="btn btn-secondary text-xs py-2 px-3">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                <span>View on Store</span>
            </a>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.products.update', $product) }}" method="POST" class="space-y-6" x-data="{
        name: '{{ old('name', $product->name) }}',
        slug: '{{ old('slug', $product->slug) }}',
        generateSlug() {
            if (!this.slug || this.slug === '') {
                this.slug = this.name.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '');
            }
        },
        tastingNotes: {{ json_encode(old('tasting_notes', $product->tasting_notes ?? [])) }},
        newNote: '',
        addNote() {
            if (this.newNote.trim()) {
                this.tastingNotes.push(this.newNote.trim());
                this.newNote = '';
            }
        },
        removeNote(index) {
            this.tastingNotes.splice(index, 1);
        },
        flavorTags: {{ json_encode(old('flavor_tags', $product->flavor_tags ?? [])) }},
        newTag: '',
        addTag() {
            if (this.newTag.trim()) {
                this.flavorTags.push(this.newTag.trim());
                this.newTag = '';
            }
        },
        removeTag(index) {
            this.flavorTags.splice(index, 1);
        }
    }">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Left 2 Cols: Main Info -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Basic Information Card -->
                <div class="card-modern space-y-4">
                    <h4 class="text-xs font-bold font-mono text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-2">Product Identification</h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="name">Product Name (EN) <span class="text-rose-500">*</span></label>
                            <input type="text" id="name" name="name" x-model="name" value="{{ old('name', $product->name) }}" required>
                        </div>
                        <div>
                            <label for="name_id">Product Name (ID)</label>
                            <input type="text" id="name_id" name="name_id" value="{{ old('name_id', $product->name_id) }}">
                        </div>
                    </div>

                    <div>
                        <label for="slug">URL Slug <span class="text-rose-500">*</span></label>
                        <div class="flex rounded-lg border border-slate-200 overflow-hidden focus-within:border-slate-900 focus-within:ring-2 focus-within:ring-slate-900/10 transition-all bg-white shadow-2xs">
                            <span class="inline-flex items-center px-3 bg-slate-50 text-slate-500 font-mono text-xs border-r border-slate-200 select-none">/store/</span>
                            <input type="text" id="slug" name="slug" x-model="slug" value="{{ old('slug', $product->slug) }}" class="border-0 focus:ring-0 focus:outline-none font-mono text-xs px-3 py-2 flex-1" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="category">Bean Category <span class="text-rose-500">*</span></label>
                            <select id="category" name="category" required>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat }}" {{ old('category', $product->category) === $cat ? 'selected' : '' }}>
                                        {{ ucfirst($cat) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="roast_level">Roast Level <span class="text-rose-500">*</span></label>
                            <select id="roast_level" name="roast_level" required>
                                @foreach ($roastLevels as $rl)
                                    <option value="{{ $rl }}" {{ old('roast_level', $product->roast_level) === $rl ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $rl)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="description">Description (EN)</label>
                        <textarea id="description" name="description" rows="3">{{ old('description', $product->description) }}</textarea>
                    </div>

                    <div>
                        <label for="description_id">Description (ID)</label>
                        <textarea id="description_id" name="description_id" rows="3">{{ old('description_id', $product->description_id) }}</textarea>
                    </div>
                </div>

                <!-- Coffee Profile Card -->
                <div class="card-modern space-y-4">
                    <h4 class="text-xs font-bold font-mono text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-2">Specialty Coffee Profile</h4>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="origin">Region / Origin</label>
                            <input type="text" id="origin" name="origin" value="{{ old('origin', $product->origin) }}" placeholder="e.g. Takengon, Aceh">
                        </div>
                        <div>
                            <label for="altitude">Altitude</label>
                            <input type="text" id="altitude" name="altitude" value="{{ old('altitude', $product->altitude) }}" placeholder="e.g. 1,450 - 1,600 MASL">
                        </div>
                        <div>
                            <label for="process">Processing Method</label>
                            <input type="text" id="process" name="process" value="{{ old('process', $product->process) }}" placeholder="e.g. Natural Anaerobic">
                        </div>
                    </div>

                    <div>
                        <label for="sca_score">SCA Cupping Score</label>
                        <input type="text" id="sca_score" name="sca_score" value="{{ old('sca_score', $product->sca_score) }}" placeholder="e.g. 88.5" class="font-mono">
                    </div>

                    <!-- Tasting Notes Tags -->
                    <div>
                        <label>Tasting Notes (e.g. Jasmine, Wild Berries, Dark Chocolate)</label>
                        <div class="flex items-center gap-2 mb-2">
                            <input type="text" x-model="newNote" @keydown.enter.prevent="addNote" placeholder="Type a note and press Add" class="text-xs">
                            <button type="button" @click="addNote" class="btn btn-secondary text-xs py-2 px-3.5 shrink-0">Add</button>
                        </div>
                        <div class="flex flex-wrap gap-1.5 min-h-[38px] p-2.5 bg-slate-50 border border-slate-200 rounded-lg">
                            <template x-for="(note, index) in tastingNotes" :key="index">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-white border border-slate-200 text-xs text-slate-800 shadow-2xs font-medium">
                                    <span x-text="note"></span>
                                    <input type="hidden" name="tasting_notes[]" :value="note">
                                    <button type="button" @click="removeNote(index)" class="text-slate-400 hover:text-rose-600 font-bold ml-1 cursor-pointer">&times;</button>
                                </span>
                            </template>
                            <span x-show="tastingNotes.length === 0" class="text-xs text-slate-400 italic py-1">No tasting notes added yet.</span>
                        </div>
                    </div>

                    <!-- Flavor Tags -->
                    <div>
                        <label>Flavor Tags (e.g. Fruity, Floral, Chocolaty)</label>
                        <div class="flex items-center gap-2 mb-2">
                            <input type="text" x-model="newTag" @keydown.enter.prevent="addTag" placeholder="Type a flavor tag and press Add" class="text-xs">
                            <button type="button" @click="addTag" class="btn btn-secondary text-xs py-2 px-3.5 shrink-0">Add</button>
                        </div>
                        <div class="flex flex-wrap gap-1.5 min-h-[38px] p-2.5 bg-slate-50 border border-slate-200 rounded-lg">
                            <template x-for="(tag, index) in flavorTags" :key="index">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-white border border-slate-200 text-xs text-slate-800 shadow-2xs font-medium">
                                    <span x-text="tag"></span>
                                    <input type="hidden" name="flavor_tags[]" :value="tag">
                                    <button type="button" @click="removeTag(index)" class="text-slate-400 hover:text-rose-600 font-bold ml-1 cursor-pointer">&times;</button>
                                </span>
                            </template>
                            <span x-show="flavorTags.length === 0" class="text-xs text-slate-400 italic py-1">No flavor tags added yet.</span>
                        </div>
                    </div>

                    <!-- Recommended Brews -->
                    <div>
                        <label class="mb-2">Recommended Brewing Methods</label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                            @php
                                $brewOptions = ['v60' => 'V60 Pour Over', 'espresso' => 'Espresso', 'tubruk' => 'Tubruk / Cupping', 'cold_brew' => 'Cold Brew', 'french_press' => 'French Press', 'aeropress' => 'AeroPress'];
                                $selectedBrews = old('recommended_brews', $product->recommended_brews ?? ['v60', 'espresso']);
                            @endphp
                            @foreach ($brewOptions as $key => $brewLabel)
                                <label class="flex items-center gap-2 p-2.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 cursor-pointer text-xs font-medium select-none">
                                    <input type="checkbox" name="recommended_brews[]" value="{{ $key }}" {{ in_array($key, $selectedBrews) ? 'checked' : '' }} class="rounded text-emerald-600 focus:ring-emerald-500 w-4 h-4">
                                    <span>{{ $brewLabel }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right 1 Col: Pricing, Inventory & Media -->
            <div class="space-y-6">

                <!-- Pricing & Multi-Weight -->
                <div class="card-modern space-y-4">
                    <h4 class="text-xs font-bold font-mono text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-2">Pricing & Inventory</h4>

                    <div>
                        <label for="base_price_200g">Base Price (200g) <span class="text-rose-500">*</span></label>
                        <div class="flex rounded-lg border border-slate-200 overflow-hidden focus-within:border-slate-900 focus-within:ring-2 focus-within:ring-slate-900/10 transition-all bg-white shadow-2xs">
                            <span class="inline-flex items-center px-3 bg-slate-50 text-slate-500 font-mono text-xs border-r border-slate-200 select-none">Rp</span>
                            <input type="number" id="base_price_200g" name="base_price_200g" value="{{ old('base_price_200g', $product->base_price_200g) }}" class="border-0 focus:ring-0 focus:outline-none font-mono text-xs px-3 py-2 flex-1" min="0" required>
                        </div>
                    </div>

                    <div>
                        <label for="price_500g">Price (500g) <span class="text-rose-500">*</span></label>
                        <div class="flex rounded-lg border border-slate-200 overflow-hidden focus-within:border-slate-900 focus-within:ring-2 focus-within:ring-slate-900/10 transition-all bg-white shadow-2xs">
                            <span class="inline-flex items-center px-3 bg-slate-50 text-slate-500 font-mono text-xs border-r border-slate-200 select-none">Rp</span>
                            <input type="number" id="price_500g" name="price_500g" value="{{ old('price_500g', $product->price_500g) }}" class="border-0 focus:ring-0 focus:outline-none font-mono text-xs px-3 py-2 flex-1" min="0" required>
                        </div>
                    </div>

                    <div>
                        <label for="price_1kg">Price (1kg) <span class="text-rose-500">*</span></label>
                        <div class="flex rounded-lg border border-slate-200 overflow-hidden focus-within:border-slate-900 focus-within:ring-2 focus-within:ring-slate-900/10 transition-all bg-white shadow-2xs">
                            <span class="inline-flex items-center px-3 bg-slate-50 text-slate-500 font-mono text-xs border-r border-slate-200 select-none">Rp</span>
                            <input type="number" id="price_1kg" name="price_1kg" value="{{ old('price_1kg', $product->price_1kg) }}" class="border-0 focus:ring-0 focus:outline-none font-mono text-xs px-3 py-2 flex-1" min="0" required>
                        </div>
                    </div>

                    <div>
                        <label for="stock">Current Stock (units) <span class="text-rose-500">*</span></label>
                        <input type="number" id="stock" name="stock" value="{{ old('stock', $product->stock) }}" min="0" required class="font-mono text-xs">
                    </div>
                </div>

                <!-- Product Image -->
                <div class="card-modern space-y-4">
                    <h4 class="text-xs font-bold font-mono text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-2">Product Media</h4>

                    <x-admin.image-uploader 
                        name="image" 
                        :value="old('image', $product->image)" 
                        context="store" 
                        label="Main Product Shot" 
                        hint="Square (1:1) packaging photo recommended. Max 5MB." 
                    />
                </div>

                <!-- Visibility & Publishing -->
                <div class="card-modern space-y-4">
                    <h4 class="text-xs font-bold font-mono text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-2">Storefront Status</h4>

                    <label class="flex items-center gap-3 p-3 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 cursor-pointer select-none">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }} class="w-4 h-4 text-emerald-600 rounded">
                        <div>
                            <span class="text-xs font-semibold text-slate-800 block">Active in Store</span>
                            <span class="text-[11px] text-slate-500 block">Show this product to buyers in catalog</span>
                        </div>
                    </label>

                    <label class="flex items-center gap-3 p-3 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 cursor-pointer select-none">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} class="w-4 h-4 text-amber-600 rounded">
                        <div>
                            <span class="text-xs font-semibold text-slate-800 block">Featured Specialty</span>
                            <span class="text-[11px] text-slate-500 block">Highlight on storefront top shelves</span>
                        </div>
                    </label>

                    <div class="pt-2">
                        <button type="submit" class="btn btn-primary w-full py-2.5 shadow-2xs font-semibold text-xs cursor-pointer">
                            Update Product
                        </button>
                    </div>
                </div>

            </div>

        </div>
    </form>

</div>
@endsection
