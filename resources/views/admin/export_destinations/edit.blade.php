@extends('admin.layouts.app')
@section('title', 'Edit Export Map Location')
@section('page_title', 'Edit Export Map Location')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-lg font-bold text-slate-900">Edit Destination: {{ $destination->name }}</h2>
        <a href="{{ route('admin.export-destinations.index') }}" class="btn btn-secondary text-xs">
            &larr; Back to Destinations List
        </a>
    </div>

    <form action="{{ route('admin.export-destinations.update', $destination) }}" method="POST" class="bg-white p-6 rounded-lg border border-slate-200 shadow-2xs space-y-5">
        @csrf
        @method('PUT')

        {{-- Country Selector --}}
        <div>
            <label for="country_select">Select Country *</label>
            <div class="relative">
                <div id="country-flag-preview" class="absolute left-3 top-1/2 -translate-y-1/2 flex items-center pointer-events-none">
                    <span id="flag-img-wrapper">
                        <img id="flag-preview"
                            src="https://flagcdn.com/w40/{{ old('country_code', $destination->country_code) }}.png"
                            alt="{{ old('country_code', $destination->country_code) }}"
                            class="w-5 h-4 object-cover rounded-sm shadow-sm">
                    </span>
                </div>
                <select id="country_select" class="pl-10 @error('name') border-rose-500 @enderror" onchange="onCountrySelect(this.value)">
                    <option value="">— Choose a country —</option>
                </select>
            </div>
            @error('name')
                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Auto-filled Fields --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="name">Country Name (English) *</label>
                <input type="text" name="name" id="name"
                    value="{{ old('name', $destination->name) }}" required
                    placeholder="Auto-filled from selection"
                    class="@error('name') border-rose-500 @enderror bg-slate-50 font-medium">
                @error('name')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="name_id">Nama Negara (Indonesia)</label>
                <input type="text" name="name_id" id="name_id"
                    value="{{ old('name_id', $destination->name_id) }}"
                    placeholder="Otomatis dari pilihan"
                    class="@error('name_id') border-rose-500 @enderror bg-slate-50 font-medium">
                @error('name_id')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Coordinate & Code Fields --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label for="country_code">Country Code (ISO 2)</label>
                <div class="relative">
                    <span id="flag-code-preview" class="absolute left-3 top-1/2 -translate-y-1/2">
                        <img id="flag-code-img"
                            src="https://flagcdn.com/w40/{{ old('country_code', $destination->country_code) }}.png"
                            alt="" class="w-5 h-4 object-cover rounded-sm">
                    </span>
                    <input type="text" name="country_code" id="country_code"
                        value="{{ old('country_code', $destination->country_code) }}"
                        placeholder="e.g. jp" maxlength="5" required
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
                    value="{{ old('longitude', $destination->longitude) }}" required
                    class="@error('longitude') border-rose-500 @enderror font-mono bg-slate-50">
                @error('longitude')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="latitude">Latitude (Y)</label>
                <input type="number" step="any" name="latitude" id="latitude"
                    value="{{ old('latitude', $destination->latitude) }}" required
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
                <textarea name="description" id="description" rows="4" class="resize-none">{{ old('description', $destination->description) }}</textarea>
                @error('description')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description_id" class="flex items-center gap-2">
                    <img src="https://flagcdn.com/w20/id.png" class="h-3 rounded-sm" alt="ID"> Deskripsi (Bahasa Indonesia)
                </label>
                <textarea name="description_id" id="description_id" rows="4" class="resize-none">{{ old('description_id', $destination->description_id) }}</textarea>
                @error('description_id')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Settings --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-center pt-2">
            <div>
                <label for="order">Display Order (Priority)</label>
                <input type="number" name="order" id="order" value="{{ old('order', $destination->order) }}" class="font-mono">
            </div>

            <div class="pt-5">
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $destination->is_active) ? 'checked' : '' }}
                        class="w-4 h-4 text-emerald-600 rounded border-slate-300">
                    <span class="text-xs font-bold text-slate-800">Display on export map</span>
                </label>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
            <a href="{{ route('admin.export-destinations.index') }}" class="btn btn-secondary text-xs">Cancel</a>
            <button type="submit" class="btn btn-primary text-xs">Update Destination</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
@include('admin.export_destinations.partials.countries-js')
<script>
    // Populate the select and pre-select current value
    const sel = document.getElementById('country_select');
    const currentCode = '{{ old('country_code', $destination->country_code) }}';

    COUNTRIES.sort((a, b) => a.en.localeCompare(b.en)).forEach(c => {
        const opt = document.createElement('option');
        opt.value = c.code;
        opt.textContent = `${c.en} / ${c.id}`;
        if (c.code === currentCode) opt.selected = true;
        sel.appendChild(opt);
    });

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
        if (!code || code.length < 2) return;
        const lower = code.toLowerCase();
        const flagSrc = `https://flagcdn.com/w40/${lower}.png`;
        document.getElementById('flag-preview').src = flagSrc;
        document.getElementById('flag-code-img').src = flagSrc;
    }
</script>
@endpush
