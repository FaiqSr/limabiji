@extends('admin.layouts.app')
@php $isCreating = !$origin->exists; @endphp
@section('title', $isCreating ? 'New Origin' : 'Edit ' . $origin->name)
@section('page_title', $isCreating ? 'Create Coffee Origin' : 'Edit Origin: ' . $origin->name)

@section('content')
<form action="{{ $isCreating ? route('admin.origins.store') : route('admin.origins.update', $origin) }}" method="POST">
    @csrf
    @if (!$isCreating)
        @method('PUT')
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Left Column: Primary Information -->
        <div class="card-modern space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-base font-semibold text-slate-900">Origin Regional Details</h3>
                <a href="{{ route('admin.origins.index') }}" class="text-xs text-slate-500 hover:text-indigo-600 font-medium flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Origins
                </a>
            </div>

            <div>
                <label for="name">Region Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $origin->name) }}" required placeholder="e.g. Toraja" class="mt-1">
            </div>

            <div>
                <label for="slug">URL Slug</label>
                <div class="relative mt-1">
                    <!-- <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-xs font-mono text-slate-400">/origin/</span> -->
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $origin->slug) }}" required placeholder="toraja" class="pl-16">
                </div>
            </div>

            <div>
                <label for="province">Province</label>
                <input type="text" name="province" id="province" value="{{ old('province', $origin->province) }}" required placeholder="South Sulawesi" class="mt-1">
            </div>

            <div>
                <x-admin.image-uploader
                    name="image"
                    :value="$origin->image"
                    context="origins"
                    label="Featured Image" />
            </div>

            <div class="pt-4 border-t border-slate-100">
                <x-admin.gallery-uploader
                    name="gallery"
                    :value="$origin->gallery"
                    context="origins"
                    label="Origin Gallery Images" />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="altitude">Altitude Range</label>
                    <input type="text" name="altitude" id="altitude" value="{{ old('altitude', $origin->altitude) }}" required placeholder="1400–1900m" class="mt-1">
                </div>
                <div>
                    <label for="score">Cupping Score</label>
                    <input type="text" name="score" id="score" value="{{ old('score', $origin->score) }}" required placeholder="86+" class="mt-1">
                </div>
                <div>
                    <label for="process">Processing Method</label>
                    <input type="text" name="process" id="process" value="{{ old('process', $origin->process) }}" required placeholder="Washed, Honey" class="mt-1">
                </div>
                <div>
                    <label for="harvest">Harvest Period</label>
                    <input type="text" name="harvest" id="harvest" value="{{ old('harvest', $origin->harvest) }}" required placeholder="May – Nov" class="mt-1">
                </div>
            </div>

            <div>
                <label for="varietals">Coffee Varietals</label>
                <input type="text" name="varietals" id="varietals" value="{{ old('varietals', $origin->varietals) }}" required placeholder="S795, Typica, Lini S" class="mt-1">
            </div>
        </div>

        <!-- Right Column: Descriptions & Arrays -->
        <div class="space-y-6">
            <!-- Overview Translation Card -->
            <div class="card-modern space-y-4" x-data="{ locale: 'en' }">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-base font-semibold text-slate-900">Regional Overview</h3>
                    <div class="inline-flex bg-slate-100 p-0.5 rounded-lg border border-slate-200">
                        <button type="button" @click="locale = 'en'" :class="locale === 'en' ? 'bg-white text-indigo-600 shadow-2xs font-semibold' : 'text-slate-600 hover:text-slate-900'" class="px-2.5 py-1 text-xs rounded-md transition-all">English (EN)</button>
                        <button type="button" @click="locale = 'id'" :class="locale === 'id' ? 'bg-white text-indigo-600 shadow-2xs font-semibold' : 'text-slate-600 hover:text-slate-900'" class="px-2.5 py-1 text-xs rounded-md transition-all">Bahasa (ID)</button>
                    </div>
                </div>

                <div x-show="locale === 'en'">
                    <label for="overview">Overview (English)</label>
                    <textarea name="overview" id="overview" rows="4" class="mt-1 text-xs leading-relaxed" placeholder="Describe the region's climate, soil, and coffee profile...">{{ old('overview', $origin->overview) }}</textarea>
                </div>
                <div x-show="locale === 'id'" style="display: none;">
                    <label for="overview_id">Overview (Bahasa Indonesia)</label>
                    <textarea name="overview_id" id="overview_id" rows="4" class="mt-1 text-xs leading-relaxed" placeholder="Deskripsikan iklim, tanah, dan profil kopi daerah ini...">{{ old('overview_id', $origin->overview_id) }}</textarea>
                </div>
            </div>

            <!-- Flavor Notes Dynamic List -->
            <div class="card-modern space-y-3">
                <h3 class="text-base font-semibold text-slate-900 pb-2 border-b border-slate-100">Flavor Profile Notes</h3>
                <div x-data="{ flavors: @js(is_array(old('flavor')) ? array_values(old('flavor')) : array_values($origin->flavor ?: [])) }">
                    <template x-for="(f, i) in flavors" :key="i">
                        <div class="flex items-center gap-2 mb-2">
                            <input type="text" :name="'flavor['+i+']'" x-model="flavors[i]" class="text-xs py-1.5" placeholder="e.g. Floral, Chocolate">
                            <button type="button" @click="flavors.splice(i, 1)" class="text-xs text-rose-600 hover:text-rose-700 px-2 py-1.5 hover:bg-rose-50 rounded font-bold">✕</button>
                        </div>
                    </template>
                    <button type="button" @click="flavors.push('')" class="btn btn-secondary py-1 px-3 text-xs mt-1">+ Add Flavor Note</button>
                </div>
            </div>

            <!-- Partner Farms Dynamic List -->
            <div class="card-modern space-y-3">
                <h3 class="text-base font-semibold text-slate-900 pb-2 border-b border-slate-100">Partner Farms & Cooperatives</h3>
                <div x-data="{ farms: @js(is_array(old('farms')) ? array_values(old('farms')) : array_values($origin->farms ?: [])) }">
                    <template x-for="(f, i) in farms" :key="i">
                        <div class="flex items-center gap-2 mb-2">
                            <input type="text" :name="'farms['+i+']'" x-model="farms[i]" class="text-xs py-1.5" placeholder="e.g. Sapan Village Farm">
                            <button type="button" @click="farms.splice(i, 1)" class="text-xs text-rose-600 hover:text-rose-700 px-2 py-1.5 hover:bg-rose-50 rounded font-bold">✕</button>
                        </div>
                    </template>
                    <button type="button" @click="farms.push('')" class="btn btn-secondary py-1 px-3 text-xs mt-1">+ Add Partner Farm</button>
                </div>
            </div>

            <!-- Settings & Action -->
            <div class="card-modern">
                <h3 class="text-base font-semibold text-slate-900 mb-4 pb-2 border-b border-slate-100">Visibility & Display</h3>
                <div class="space-y-4">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ $origin->is_active ? 'checked' : '' }} class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300">
                        <span class="text-sm font-medium text-slate-700">Active Origin (Displayed on Landing Pages)</span>
                    </label>

                    <div>
                        <label for="order">Display Order Index</label>
                        <input type="number" name="order" id="order" value="{{ old('order', $origin->order ?: 0) }}" min="0" class="mt-1">
                    </div>

                    <button type="submit" class="btn btn-primary w-full py-2.5 text-sm font-medium shadow-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Save Origin
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection