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
