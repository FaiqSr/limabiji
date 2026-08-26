@extends('admin.layouts.app')
@section('title', 'Add Export Map Location')
@section('page_title', 'Add Export Map Location')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-lg font-bold text-slate-900">Add New Export Map Destination</h2>
        <a href="{{ route('admin.export-destinations.index') }}" class="btn btn-secondary text-xs">
            &larr; Back to Destinations List
        </a>
    </div>

    <form action="{{ route('admin.export-destinations.store') }}" method="POST" class="bg-white p-6 rounded-lg border border-slate-200 shadow-2xs space-y-5">
        @csrf

        {{-- Country Selector --}}
        <div>
            <label for="country_select">Select Country *</label>
            <div class="relative">
                <div id="country-flag-preview" class="absolute left-3 top-1/2 -translate-y-1/2 flex items-center pointer-events-none">
                    <span id="flag-img-wrapper" class="hidden">
                        <img id="flag-preview" src="" alt="" class="w-5 h-4 object-cover rounded-sm shadow-sm">
                    </span>
                    <svg id="flag-placeholder" class="w-5 h-5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 000 4h1v7a1 1 0 102 0v-7h1a2 2 0 000-4H3z"/>
                    </svg>
                </div>
                <select id="country_select" class="pl-20 @error('name') border-rose-500 @enderror" onchange="onCountrySelect(this.value)">
                    <option value="">— Choose a country —</option>
                </select>
            </div>
            @error('name')
                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Auto-filled Fields (readonly display, can be overridden) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="name">Country Name (English) *</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Auto-filled from selection" required
                    class="@error('name') border-rose-500 @enderror bg-slate-50 font-medium">
                @error('name')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="name_id">Nama Negara (Indonesia)</label>
                <input type="text" name="name_id" id="name_id" value="{{ old('name_id') }}" placeholder="Otomatis dari pilihan"
                    class="@error('name_id') border-rose-500 @enderror bg-slate-50 font-medium">
                @error('name_id')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Hidden country code + Coordinate fields --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label for="country_code">Country Code (ISO 2)</label>
                <div class="relative">
                    <span id="flag-code-preview" class="absolute left-3 top-1/2 -translate-y-1/2 hidden">
                        <img id="flag-code-img" src="" alt="" class="w-5 h-4 object-cover rounded-sm">
                    </span>
                    <input type="text" name="country_code" id="country_code"
                        value="{{ old('country_code') }}" placeholder="e.g. jp" maxlength="5" required
                        class="@error('country_code') border-rose-500 @enderror font-mono uppercase pl-10"
                        oninput="updateFlagFromCode(this.value)">
                </div>
                <p class="text-[11px] text-slate-400 mt-1">Auto-filled, or override manually</p>
                @error('country_code')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="longitude">Longitude (X)</label>
                <input type="number" step="any" name="longitude" id="longitude"
                    value="{{ old('longitude', 0) }}" placeholder="e.g. 138.25" required
                    class="@error('longitude') border-rose-500 @enderror font-mono bg-slate-50">
                @error('longitude')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="latitude">Latitude (Y)</label>
                <input type="number" step="any" name="latitude" id="latitude"
                    value="{{ old('latitude', 0) }}" placeholder="e.g. 36.20" required
                    class="@error('latitude') border-rose-500 @enderror font-mono bg-slate-50">
                @error('latitude')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Description Bilingual --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div>
                <label for="description" class="flex items-center gap-2">
                    <img src="https://flagcdn.com/w20/gb.png" class="h-3 rounded-sm" alt="EN"> Description (English)
                </label>
                <textarea name="description" id="description" rows="4"
                    placeholder="Describe this export market in English…" class="resize-none">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description_id" class="flex items-center gap-2">
                    <img src="https://flagcdn.com/w20/id.png" class="h-3 rounded-sm" alt="ID"> Deskripsi (Bahasa Indonesia)
                </label>
                <textarea name="description_id" id="description_id" rows="4"
                    placeholder="Deskripsikan pasar ekspor ini dalam Bahasa Indonesia…" class="resize-none">{{ old('description_id') }}</textarea>
                @error('description_id')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Settings --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-center pt-2">
            <div>
                <label for="order">Display Order (Priority)</label>
                <input type="number" name="order" id="order" value="{{ old('order', 0) }}" class="font-mono">
            </div>

            <div class="pt-5">
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                        class="w-4 h-4 text-emerald-600 rounded border-slate-300">
                    <span class="text-xs font-bold text-slate-800">Display on export map</span>
                </label>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
            <a href="{{ route('admin.export-destinations.index') }}" class="btn btn-secondary text-xs">Cancel</a>
            <button type="submit" class="btn btn-primary text-xs">Save Destination</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
@include('admin.export_destinations.partials.countries-js')
<script>
    // Populate the select dropdown
    const sel = document.getElementById('country_select');
    COUNTRIES.sort((a, b) => a.en.localeCompare(b.en)).forEach(c => {
        const opt = document.createElement('option');
        opt.value = c.code;
        opt.textContent = `${c.en} / ${c.id}`;
        sel.appendChild(opt);
    });

    // Prefill if old() value exists
    const oldCode = '{{ old('country_code') }}';
    if (oldCode) {
        sel.value = oldCode;
        updateFlagFromCode(oldCode);
    }

    function onCountrySelect(code) {
        if (!code) return;
        const country = COUNTRIES.find(c => c.code === code);
        if (!country) return;

        document.getElementById('name').value = country.en;
        document.getElementById('name_id').value = country.id;
        document.getElementById('country_code').value = country.code;
        document.getElementById('longitude').value = country.lon;
        document.getElementById('latitude').value = country.lat;
        updateFlagFromCode(country.code);
    }

    function updateFlagFromCode(code) {
        if (!code || code.length < 2) {
            document.getElementById('flag-img-wrapper').classList.add('hidden');
            document.getElementById('flag-placeholder').classList.remove('hidden');
            document.getElementById('flag-code-preview').classList.add('hidden');
            return;
        }
        const lower = code.toLowerCase();
        const flagSrc = `https://flagcdn.com/w40/${lower}.png`;

        document.getElementById('flag-preview').src = flagSrc;
        document.getElementById('flag-preview').alt = lower;
        document.getElementById('flag-img-wrapper').classList.remove('hidden');
        document.getElementById('flag-placeholder').classList.add('hidden');

        document.getElementById('flag-code-img').src = flagSrc;
        document.getElementById('flag-code-preview').classList.remove('hidden');
    }
</script>
@endpush
