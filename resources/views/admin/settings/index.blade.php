@extends('admin.layouts.app')
@section('title', 'Site Settings')
@section('page_title', 'Global Settings & Contact Information')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Header Banner -->
    <div class="card-modern">
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-xl bg-slate-900 text-white flex items-center justify-center shrink-0 shadow-2xs">
                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-base font-semibold text-slate-900">Website & Contact Configurations</h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Manage direct export contact information, phone/WhatsApp numbers, addresses, and global brand identifiers across all landing pages.
                </p>
            </div>
        </div>
    </div>

    <!-- Main Settings Form -->
    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- 1. Contact & Communications Card -->
            <div class="card-modern space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span>
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider font-mono">Contact & Support</h3>
                    </div>
                    <span class="text-[10px] font-mono text-slate-400">Global Details</span>
                </div>

                <div>
                    <label for="contact_email" class="block text-xs font-semibold text-slate-700 mb-1">
                        Export Email Address <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="email" 
                               name="contact_email" 
                               id="contact_email" 
                               value="{{ old('contact_email', $settings['contact_email']) }}" 
                               required 
                               placeholder="export@limabijiagritech.com" 
                               class="w-full text-xs bg-slate-50 border @error('contact_email') border-rose-500 @else border-slate-200 @enderror rounded-xl p-3 pl-9 focus:bg-white focus:ring-2 focus:ring-indigo-500 transition-all font-mono">
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1">Displayed in contact hero, export inquiry block, and footer.</p>
                    @error('contact_email')
                        <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="contact_phone" class="block text-xs font-semibold text-slate-700 mb-1">
                        Phone / WhatsApp Number <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="text" 
                               name="contact_phone" 
                               id="contact_phone" 
                               value="{{ old('contact_phone', $settings['contact_phone']) }}" 
                               required 
                               placeholder="+62 812 3456 7890" 
                               class="w-full text-xs bg-slate-50 border @error('contact_phone') border-rose-500 @else border-slate-200 @enderror rounded-xl p-3 pl-9 focus:bg-white focus:ring-2 focus:ring-indigo-500 transition-all font-mono">
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1">International format with country code (e.g. +62 812 3456 7890).</p>
                    @error('contact_phone')
                        <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="contact_hours" class="block text-xs font-semibold text-slate-700 mb-1">
                        Operating / Support Hours
                    </label>
                    <input type="text" 
                           name="contact_hours" 
                           id="contact_hours" 
                           value="{{ old('contact_hours', $settings['contact_hours']) }}" 
                           placeholder="Mon – Fri, 8:00 – 16:00 WIB" 
                           class="w-full text-xs bg-slate-50 border @error('contact_hours') border-rose-500 @else border-slate-200 @enderror rounded-xl p-3 focus:bg-white focus:ring-2 focus:ring-indigo-500 transition-all">
                    @error('contact_hours')
                        <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- 2. Export Office Address Card -->
            <div class="card-modern space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider font-mono">Office Address</h3>
                    </div>
                    <span class="text-[10px] font-mono text-slate-400">Bilingual (EN / ID)</span>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label for="contact_address_en" class="text-xs font-semibold text-slate-700">Office Address (English)</label>
                        <span class="px-1.5 py-0.2 rounded bg-slate-100 font-mono text-[9px] font-bold text-slate-600">EN</span>
                    </div>
                    <textarea name="contact_address_en" 
                              id="contact_address_en" 
                              rows="3" 
                              placeholder="Bogor, West Java, Indonesia" 
                              class="w-full text-xs bg-slate-50 border @error('contact_address_en') border-rose-500 @else border-slate-200 @enderror rounded-xl p-3 focus:bg-white focus:ring-2 focus:ring-indigo-500 transition-all">{{ old('contact_address_en', $settings['contact_address_en']) }}</textarea>
                    @error('contact_address_en')
                        <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label for="contact_address_id" class="text-xs font-semibold text-slate-700">Alamat Kantor (Bahasa Indonesia)</label>
                        <span class="px-1.5 py-0.2 rounded bg-slate-100 font-mono text-[9px] font-bold text-slate-600">ID</span>
                    </div>
                    <textarea name="contact_address_id" 
                              id="contact_address_id" 
                              rows="3" 
                              placeholder="Bogor, Jawa Barat, Indonesia" 
                              class="w-full text-xs bg-slate-50 border @error('contact_address_id') border-rose-500 @else border-slate-200 @enderror rounded-xl p-3 focus:bg-white focus:ring-2 focus:ring-indigo-500 transition-all">{{ old('contact_address_id', $settings['contact_address_id']) }}</textarea>
                    @error('contact_address_id')
                        <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- 3. General Brand Configuration Card -->
        <div class="card-modern space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-slate-900"></span>
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider font-mono">Brand & Identity</h3>
                </div>
                <span class="text-[10px] font-mono text-slate-400">General</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="site_name_en" class="block text-xs font-semibold text-slate-700 mb-1">Site / Brand Name (EN)</label>
                    <input type="text" 
                           name="site_name_en" 
                           id="site_name_en" 
                           value="{{ old('site_name_en', $settings['site_name_en']) }}" 
                           placeholder="Lima Biji Agritech" 
                           class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl p-3 focus:bg-white focus:ring-2 focus:ring-indigo-500 transition-all">
                </div>

                <div>
                    <label for="site_name_id" class="block text-xs font-semibold text-slate-700 mb-1">Nama Brand (ID)</label>
                    <input type="text" 
                           name="site_name_id" 
                           id="site_name_id" 
                           value="{{ old('site_name_id', $settings['site_name_id']) }}" 
                           placeholder="Lima Biji Agritech" 
                           class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl p-3 focus:bg-white focus:ring-2 focus:ring-indigo-500 transition-all">
                </div>
            </div>
        </div>

        <!-- 4. Store Location & Interactive Map Configuration Card -->
        <div class="card-modern space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider font-mono">Store Location & Map (Contact Page)</h3>
                </div>
                <span class="text-[10px] font-mono text-slate-400">Google Maps Embed</span>
            </div>

            <p class="text-xs text-slate-500">
                Configure the store / office map pin displayed on the public Contact page (<code class="font-mono text-emerald-600 font-semibold">/contact</code>).
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="map_lat" class="block text-xs font-semibold text-slate-700 mb-1">
                        Latitude <span class="text-slate-400 font-normal">(-90 to 90)</span>
                    </label>
                    <input type="number" 
                           step="any"
                           name="map_lat" 
                           id="map_lat" 
                           value="{{ old('map_lat', $settings['map_lat']) }}" 
                           placeholder="-6.597144" 
                           class="w-full text-xs bg-slate-50 border @error('map_lat') border-rose-500 @else border-slate-200 @enderror rounded-xl p-3 focus:bg-white focus:ring-2 focus:ring-indigo-500 font-mono transition-all">
                    @error('map_lat')
                        <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="map_lng" class="block text-xs font-semibold text-slate-700 mb-1">
                        Longitude <span class="text-slate-400 font-normal">(-180 to 180)</span>
                    </label>
                    <input type="number" 
                           step="any"
                           name="map_lng" 
                           id="map_lng" 
                           value="{{ old('map_lng', $settings['map_lng']) }}" 
                           placeholder="106.806039" 
                           class="w-full text-xs bg-slate-50 border @error('map_lng') border-rose-500 @else border-slate-200 @enderror rounded-xl p-3 focus:bg-white focus:ring-2 focus:ring-indigo-500 font-mono transition-all">
                    @error('map_lng')
                        <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="map_zoom" class="block text-xs font-semibold text-slate-700 mb-1">
                        Zoom Level <span class="text-slate-400 font-normal">(1 - 20)</span>
                    </label>
                    <input type="number" 
                           min="1"
                           max="20"
                           name="map_zoom" 
                           id="map_zoom" 
                           value="{{ old('map_zoom', $settings['map_zoom']) }}" 
                           placeholder="14" 
                           class="w-full text-xs bg-slate-50 border @error('map_zoom') border-rose-500 @else border-slate-200 @enderror rounded-xl p-3 focus:bg-white focus:ring-2 focus:ring-indigo-500 font-mono transition-all">
                    @error('map_zoom')
                        <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="map_label" class="block text-xs font-semibold text-slate-700 mb-1">
                        Marker Pin Label / Store Name
                    </label>
                    <input type="text" 
                           name="map_label" 
                           id="map_label" 
                           value="{{ old('map_label', $settings['map_label']) }}" 
                           placeholder="Lima Biji Agritech — Headquarters" 
                           class="w-full text-xs bg-slate-50 border @error('map_label') border-rose-500 @else border-slate-200 @enderror rounded-xl p-3 focus:bg-white focus:ring-2 focus:ring-indigo-500 transition-all">
                    @error('map_label')
                        <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="map_embed_url" class="block text-xs font-semibold text-slate-700 mb-1">
                        Custom Google Maps Embed URL <span class="text-slate-400 font-normal">(Opsional)</span>
                    </label>
                    <input type="url" 
                           name="map_embed_url" 
                           id="map_embed_url" 
                           value="{{ old('map_embed_url', $settings['map_embed_url']) }}" 
                           placeholder="https://www.google.com/maps/embed?pb=..." 
                           class="w-full text-xs bg-slate-50 border @error('map_embed_url') border-rose-500 @else border-slate-200 @enderror rounded-xl p-3 focus:bg-white focus:ring-2 focus:ring-indigo-500 font-mono text-[11px] transition-all">
                    @error('map_embed_url')
                        <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="bg-slate-50 rounded-xl p-3 text-[11px] text-slate-600 flex items-center justify-between border border-slate-100">
                <span class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Tip: Jika Custom Embed URL dikosongkan, sistem akan otomatis menggunakan Latitude & Longitude untuk menampilkan peta.
                </span>
                <a href="https://www.google.com/maps" target="_blank" rel="noopener" class="text-indigo-600 hover:text-indigo-700 font-medium inline-flex items-center gap-1">
                    Buka Google Maps
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Page Statistics & Metrics Card -->
        <div class="card-modern space-y-5">
            <div class="flex items-center gap-3 pb-3 border-b border-slate-100">
                <div class="w-8 h-8 rounded-lg bg-amber-50 border border-amber-100 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-slate-900">Page Statistics & Metrics</h3>
                    <p class="text-[11px] text-slate-500">Kelola angka metrik untuk halaman <span class="font-mono bg-slate-100 px-1 rounded">/about</span> dan <span class="font-mono bg-slate-100 px-1 rounded">/innovation</span>.</p>
                </div>
            </div>

            <!-- About Page Section -->
            <div class="space-y-3">
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-indigo-50 text-indigo-700 border border-indigo-100">About Page</span>
                    <span class="text-xs text-slate-400">/about</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="stat_about_sca_score" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            SCA Cupping Score (/about)
                        </label>
                        <input type="text"
                               id="stat_about_sca_score"
                               name="stat_about_sca_score"
                               value="{{ old('stat_about_sca_score', $settings['stat_about_sca_score']) }}"
                               placeholder="e.g. 82+"
                               maxlength="20"
                               class="w-full text-xs bg-slate-50 border @error('stat_about_sca_score') border-rose-500 @else border-slate-200 @enderror rounded-xl p-3 focus:bg-white focus:ring-2 focus:ring-indigo-500 font-mono transition-all">
                        @error('stat_about_sca_score')
                            <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                        <p class="text-[11px] text-slate-400 mt-1">Tampil di badge Quality Benchmark pada halaman About.</p>
                    </div>
                </div>
            </div>

            <div class="border-t border-slate-100 pt-4 space-y-3">
                <!-- Innovation Page Section -->
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-100">Innovation Page</span>
                    <span class="text-xs text-slate-400">/innovation</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- SCA Score for Innovation -->
                    <div>
                        <label for="stat_innovation_sca_score" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            SCA Cupping Score (/innovation)
                        </label>
                        <input type="text"
                               id="stat_innovation_sca_score"
                               name="stat_innovation_sca_score"
                               value="{{ old('stat_innovation_sca_score', $settings['stat_innovation_sca_score']) }}"
                               placeholder="e.g. 82+"
                               maxlength="20"
                               class="w-full text-xs bg-slate-50 border @error('stat_innovation_sca_score') border-rose-500 @else border-slate-200 @enderror rounded-xl p-3 focus:bg-white focus:ring-2 focus:ring-indigo-500 font-mono transition-all">
                        @error('stat_innovation_sca_score')
                            <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                        <p class="text-[11px] text-slate-400 mt-1">Tampil pada stat grid cupping score di halaman Inovasi.</p>
                    </div>

                    <!-- Export Destinations -->
                    <div>
                        <label for="stat_export_destinations" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Export Destinations (/innovation)
                        </label>
                        <input type="text"
                               id="stat_export_destinations"
                               name="stat_export_destinations"
                               value="{{ old('stat_export_destinations', $settings['stat_export_destinations']) }}"
                               placeholder="e.g. 7+"
                               maxlength="20"
                               class="w-full text-xs bg-slate-50 border @error('stat_export_destinations') border-rose-500 @else border-slate-200 @enderror rounded-xl p-3 focus:bg-white focus:ring-2 focus:ring-indigo-500 font-mono transition-all">
                        @error('stat_export_destinations')
                            <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                        <p class="text-[11px] text-slate-400 mt-1">Tampil pada stat grid destinasi ekspor di halaman Inovasi.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Save Button Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-2">
            <p class="text-xs text-slate-500">
                All changes take effect immediately across all public landing pages and contact forms.
            </p>
            <button type="submit" class="btn btn-primary text-xs py-3 px-6 shadow-xs flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>Save Site Settings</span>
            </button>
        </div>
    </form>
</div>
@endsection
