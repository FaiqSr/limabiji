@extends('admin.layouts.app')
@section('title', 'Add Certificate')
@section('page_title', 'Create Quality Certificate')

@section('content')
<form action="{{ route('admin.certificates.store') }}" method="POST" enctype="multipart/form-data" class="max-w-4xl mx-auto space-y-6">
    @csrf

    <div class="flex items-center justify-between">
        <a href="{{ route('admin.certificates.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-600 hover:text-slate-900 bg-white border border-slate-200 px-3.5 py-2 rounded-xl shadow-2xs hover:bg-slate-50 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Back to Certificates</span>
        </a>
    </div>

    <!-- Main Form Card -->
    <div class="card-modern space-y-6">
        <div>
            <h3 class="text-base font-semibold text-slate-900">Certificate Details</h3>
            <p class="text-xs text-slate-500 mt-0.5">Add certification and quality compliance badges issued to Lima Biji.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <!-- Name -->
            <div class="md:col-span-2">
                <label for="name" class="block text-xs font-semibold text-slate-700 mb-1.5">
                    Certificate Name <span class="text-rose-500">*</span>
                </label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="{{ old('name') }}" 
                       required 
                       placeholder="e.g. Halal Indonesia (BPJPH) or BPOM RI MD"
                       class="w-full text-xs bg-white border @error('name') border-rose-500 @else border-slate-200 @enderror rounded-xl p-3 focus:ring-2 focus:ring-emerald-500 transition-all font-medium">
                @error('name')
                    <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Issuer -->
            <div>
                <label for="issuer" class="block text-xs font-semibold text-slate-700 mb-1.5">
                    Issuer Authority / Body
                </label>
                <input type="text" 
                       id="issuer" 
                       name="issuer" 
                       value="{{ old('issuer') }}" 
                       placeholder="e.g. BPJPH Kementerian Agama RI"
                       class="w-full text-xs bg-white border @error('issuer') border-rose-500 @else border-slate-200 @enderror rounded-xl p-3 focus:ring-2 focus:ring-emerald-500 transition-all">
                @error('issuer')
                    <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Certificate Number -->
            <div>
                <label for="certificate_number" class="block text-xs font-semibold text-slate-700 mb-1.5">
                    Certificate Number <span class="text-rose-500">*</span>
                </label>
                <input type="text" 
                       id="certificate_number" 
                       name="certificate_number" 
                       value="{{ old('certificate_number') }}" 
                       required 
                       placeholder="e.g. ID32110001234560723"
                       class="w-full text-xs bg-white border @error('certificate_number') border-rose-500 @else border-slate-200 @enderror rounded-xl p-3 focus:ring-2 focus:ring-emerald-500 transition-all font-mono">
                @error('certificate_number')
                    <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Logo URL -->
            <div>
                <label for="logo" class="block text-xs font-semibold text-slate-700 mb-1.5">
                    Logo / Image URL
                </label>
                <input type="url" 
                       id="logo" 
                       name="logo" 
                       value="{{ old('logo') }}" 
                       placeholder="https://..."
                       class="w-full text-xs bg-white border @error('logo') border-rose-500 @else border-slate-200 @enderror rounded-xl p-3 focus:ring-2 focus:ring-emerald-500 transition-all font-mono">
                @error('logo')
                    <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Logo File Upload -->
            <div>
                <label for="logo_file" class="block text-xs font-semibold text-slate-700 mb-1.5">
                    Or Upload Logo Image
                </label>
                <input type="file" 
                       id="logo_file" 
                       name="logo_file" 
                       accept="image/*"
                       class="block w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 cursor-pointer">
                @error('logo_file')
                    <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Issued Date -->
            <div>
                <label for="issued_date" class="block text-xs font-semibold text-slate-700 mb-1.5">Issued Date</label>
                <input type="date" 
                       id="issued_date" 
                       name="issued_date" 
                       value="{{ old('issued_date') }}"
                       class="w-full text-xs bg-white border border-slate-200 rounded-xl p-2.5 focus:ring-2 focus:ring-emerald-500">
            </div>

            <!-- Expiry Date -->
            <div>
                <label for="expiry_date" class="block text-xs font-semibold text-slate-700 mb-1.5">Expiry Date (Leave empty if lifetime)</label>
                <input type="date" 
                       id="expiry_date" 
                       name="expiry_date" 
                       value="{{ old('expiry_date') }}"
                       class="w-full text-xs bg-white border border-slate-200 rounded-xl p-2.5 focus:ring-2 focus:ring-emerald-500">
            </div>

            <!-- Display Order -->
            <div>
                <label for="order" class="block text-xs font-semibold text-slate-700 mb-1.5">Display Order Index</label>
                <input type="number" 
                       id="order" 
                       name="order" 
                       value="{{ old('order', $certificate->order ?? 1) }}" 
                       min="0"
                       class="w-full text-xs bg-white border border-slate-200 rounded-xl p-2.5 focus:ring-2 focus:ring-emerald-500 font-mono">
            </div>

            <!-- Active Status -->
            <div class="pt-2">
                <label class="flex items-center gap-3 cursor-pointer p-3.5 rounded-xl bg-slate-50 border border-slate-200 hover:bg-slate-100 transition-colors">
                    <input type="checkbox" 
                           name="is_active" 
                           value="1" 
                           {{ old('is_active', true) ? 'checked' : '' }} 
                           class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 border-slate-300">
                    <div>
                        <span class="text-xs font-semibold text-slate-900 block">Publish on Landing Page</span>
                        <span class="text-[11px] text-slate-500">Active certificates are displayed on the /about page.</span>
                    </div>
                </label>
            </div>

            <!-- Description -->
            <div class="md:col-span-2">
                <label for="description" class="block text-xs font-semibold text-slate-700 mb-1.5">Scope / Description (Optional)</label>
                <textarea id="description" 
                          name="description" 
                          rows="3" 
                          placeholder="Brief notes about the certification scope..."
                          class="w-full text-xs bg-white border border-slate-200 rounded-xl p-3 focus:ring-2 focus:ring-emerald-500 leading-relaxed transition-all">{{ old('description') }}</textarea>
            </div>
        </div>

        <div class="pt-2">
            <button type="submit" class="btn btn-primary w-full py-3 text-sm font-semibold shadow-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>Save Certificate</span>
            </button>
        </div>
    </div>
</form>
@endsection
