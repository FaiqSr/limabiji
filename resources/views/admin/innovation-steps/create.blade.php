@extends('admin.layouts.app')
@section('title', 'Add Innovation Step')
@section('page_title', 'Create Process Step')

@section('content')
<form action="{{ route('admin.innovation-steps.store') }}" method="POST" x-data="{ locale: 'en' }" class="max-w-4xl mx-auto space-y-6">
    @csrf

    <div class="flex items-center justify-between">
        <a href="{{ route('admin.innovation-steps.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-600 hover:text-slate-900 bg-white border border-slate-200 px-3.5 py-2 rounded-xl shadow-2xs hover:bg-slate-50 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Back to Steps</span>
        </a>

        <!-- Language Switcher Tabs -->
        <div class="inline-flex bg-slate-100 p-1 rounded-xl border border-slate-200">
            <button type="button" @click="locale = 'en'" :class="locale === 'en' ? 'bg-white text-indigo-700 shadow-2xs font-semibold' : 'text-slate-600 hover:text-slate-900'" class="px-3 py-1.5 text-xs rounded-lg transition-all flex items-center gap-1.5">
                <span>🇺🇸 English (EN)</span>
            </button>
            <button type="button" @click="locale = 'id'" :class="locale === 'id' ? 'bg-white text-indigo-700 shadow-2xs font-semibold' : 'text-slate-600 hover:text-slate-900'" class="px-3 py-1.5 text-xs rounded-lg transition-all flex items-center gap-1.5">
                <span>🇮🇩 Indonesia (ID)</span>
            </button>
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="card-modern space-y-6">
        <!-- English Section -->
        <div x-show="locale === 'en'" class="space-y-4">
            <div>
                <label for="title" class="block text-xs font-semibold text-slate-700 mb-1.5">
                    Step Title (English) <span class="text-rose-500">*</span>
                </label>
                <input type="text" 
                       name="title" 
                       id="title" 
                       value="{{ old('title') }}" 
                       required 
                       placeholder="e.g. Ethical Cherry Sourcing" 
                       class="w-full text-xs bg-white border @error('title') border-rose-500 @else border-slate-200 @enderror rounded-xl p-3 focus:ring-2 focus:ring-indigo-500 transition-all font-medium">
                @error('title')
                    <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-xs font-semibold text-slate-700 mb-1.5">
                    Step Description (English) <span class="text-rose-500">*</span>
                </label>
                <textarea name="description" 
                          id="description" 
                          rows="4" 
                          required 
                          placeholder="Explain the technical or logistical process involved in this step..." 
                          class="w-full text-xs bg-white border @error('description') border-rose-500 @else border-slate-200 @enderror rounded-xl p-3 focus:ring-2 focus:ring-indigo-500 leading-relaxed transition-all">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="details" class="block text-xs font-semibold text-slate-700 mb-1.5">
                    Key Tag Details (English) <span class="text-slate-400 font-normal">(Comma separated)</span>
                </label>
                <input type="text" 
                       name="details" 
                       id="details" 
                       value="{{ old('details') }}" 
                       placeholder="e.g. Hand-picked red cherries, 20°+ Brix sugar level, High altitude (1200m+)" 
                       class="w-full text-xs bg-white border border-slate-200 rounded-xl p-3 focus:ring-2 focus:ring-indigo-500 transition-all">
                <p class="text-[11px] text-slate-400 mt-1">Separate multiple badges with commas. Displayed as highlighted tags beneath the description.</p>
            </div>
        </div>

        <!-- Indonesian Section -->
        <div x-show="locale === 'id'" style="display: none;" class="space-y-4">
            <div>
                <label for="title_id" class="block text-xs font-semibold text-slate-700 mb-1.5">
                    Judul Langkah (Bahasa Indonesia)
                </label>
                <input type="text" 
                       name="title_id" 
                       id="title_id" 
                       value="{{ old('title_id') }}" 
                       placeholder="Contoh: Pengadaan Ceri Etis" 
                       class="w-full text-xs bg-white border @error('title_id') border-rose-500 @else border-slate-200 @enderror rounded-xl p-3 focus:ring-2 focus:ring-indigo-500 transition-all font-medium">
                @error('title_id')
                    <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description_id" class="block text-xs font-semibold text-slate-700 mb-1.5">
                    Deskripsi Langkah (Bahasa Indonesia)
                </label>
                <textarea name="description_id" 
                          id="description_id" 
                          rows="4" 
                          placeholder="Jelaskan detail proses teknis atau logistik pada langkah ini..." 
                          class="w-full text-xs bg-white border @error('description_id') border-rose-500 @else border-slate-200 @enderror rounded-xl p-3 focus:ring-2 focus:ring-indigo-500 leading-relaxed transition-all">{{ old('description_id') }}</textarea>
                @error('description_id')
                    <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="details_id" class="block text-xs font-semibold text-slate-700 mb-1.5">
                    Detail Poin Kunci (Bahasa Indonesia) <span class="text-slate-400 font-normal">(Pisahkan dengan koma)</span>
                </label>
                <input type="text" 
                       name="details_id" 
                       id="details_id" 
                       value="{{ old('details_id') }}" 
                       placeholder="Contoh: Petik tangan ceri merah, Kadar gula 20°+ Brix, Dataran tinggi (1200m+)" 
                       class="w-full text-xs bg-white border border-slate-200 rounded-xl p-3 focus:ring-2 focus:ring-indigo-500 transition-all">
            </div>
        </div>

        <!-- Common Details -->
        <div class="pt-5 border-t border-slate-100 space-y-4">
            <div>
                <label for="image" class="block text-xs font-semibold text-slate-700 mb-1.5">
                    Step Feature Image URL
                </label>
                <input type="url" 
                       name="image" 
                       id="image" 
                       value="{{ old('image') }}" 
                       placeholder="https://images.unsplash.com/... or /storage/..." 
                       class="w-full text-xs bg-white border border-slate-200 rounded-xl p-3 focus:ring-2 focus:ring-indigo-500 transition-all font-mono">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="step_number" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Step Badge Number <span class="text-slate-400 font-normal">(e.g. 01, 02)</span>
                    </label>
                    <input type="text" 
                           name="step_number" 
                           id="step_number" 
                           value="{{ old('step_number', $suggestedStepNumber ?? '01') }}" 
                           placeholder="01" 
                           maxlength="10" 
                           class="w-full text-xs bg-white border border-slate-200 rounded-xl p-2.5 focus:ring-2 focus:ring-indigo-500 font-mono">
                </div>

                <div>
                    <label for="order" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Display Order Sequence <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" 
                           name="order" 
                           id="order" 
                           value="{{ old('order', $nextOrder ?? 1) }}" 
                           required 
                           min="1" 
                           class="w-full text-xs bg-white border border-slate-200 rounded-xl p-2.5 focus:ring-2 focus:ring-indigo-500 font-mono">
                </div>
            </div>

            <!-- Active Status -->
            <div class="pt-2">
                <label class="flex items-center gap-3 cursor-pointer p-3.5 rounded-xl bg-slate-50 border border-slate-200 hover:bg-slate-100 transition-colors">
                    <input type="checkbox" 
                           name="is_active" 
                           value="1" 
                           {{ old('is_active', true) ? 'checked' : '' }} 
                           class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300">
                    <div>
                        <span class="text-xs font-semibold text-slate-900 block">Publish on Landing Page</span>
                        <span class="text-[11px] text-slate-500">Active steps are immediately visible on the /innovation landing page.</span>
                    </div>
                </label>
            </div>
        </div>

        <div class="pt-2">
            <button type="submit" class="btn btn-primary w-full py-3 text-sm font-semibold shadow-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>Save Process Step</span>
            </button>
        </div>
    </div>
</form>
@endsection
