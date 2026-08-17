@extends('admin.layouts.app')
@section('title', 'Edit Innovation Step')
@section('page_title', 'Edit Process Step')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.innovation-steps.index') }}" class="text-xs text-slate-500 hover:text-slate-900 flex items-center gap-1 font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Process Steps
        </a>
    </div>

    <form action="{{ route('admin.innovation-steps.update', $innovationStep) }}" method="POST" class="space-y-6" x-data="{ langTab: 'en' }">
        @csrf
        @method('PUT')

        <div class="card-modern space-y-6">
            <!-- Language Switcher Tabs -->
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="flex items-center gap-2">
                    <button type="button" 
                            @click="langTab = 'en'" 
                            :class="langTab === 'en' ? 'bg-slate-900 text-white font-bold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                            class="px-3.5 py-1.5 rounded-lg text-xs font-mono transition-all flex items-center gap-1.5">
                        <span>EN</span>
                        <span class="text-[10px] opacity-75 font-sans">(English)</span>
                    </button>
                    <button type="button" 
                            @click="langTab = 'id'" 
                            :class="langTab === 'id' ? 'bg-slate-900 text-white font-bold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                            class="px-3.5 py-1.5 rounded-lg text-xs font-mono transition-all flex items-center gap-1.5">
                        <span>ID</span>
                        <span class="text-[10px] opacity-75 font-sans">(Bahasa Indonesia)</span>
                    </button>
                </div>
                <span class="text-[11px] text-slate-400 font-mono">Bilingual Step Content</span>
            </div>

            <!-- English Fields -->
            <div x-show="langTab === 'en'" class="space-y-5">
                <div>
                    <label for="title" class="block text-xs font-semibold text-slate-700 mb-1">
                        Step Title (English) <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           name="title" 
                           id="title" 
                           value="{{ old('title', $innovationStep->title) }}" 
                           placeholder="e.g. Ethical Cherry Sourcing" 
                           required 
                           class="w-full text-xs bg-slate-50 border @error('title') border-rose-500 @else border-slate-200 @enderror rounded-xl p-3 focus:bg-white focus:ring-2 focus:ring-slate-900 transition-all font-medium">
                    @error('title')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-xs font-semibold text-slate-700 mb-1">
                        Step Description (English) <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="description" 
                              id="description" 
                              rows="4" 
                              required 
                              placeholder="Explain the technical or logistical process involved in this step..." 
                              class="w-full text-xs bg-slate-50 border @error('description') border-rose-500 @else border-slate-200 @enderror rounded-xl p-3 focus:bg-white focus:ring-2 focus:ring-slate-900 transition-all">{{ old('description', $innovationStep->description) }}</textarea>
                    @error('description')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="details" class="block text-xs font-semibold text-slate-700 mb-1">
                        Key Tag Details (English) <span class="text-slate-400 font-normal">(Comma separated)</span>
                    </label>
                    <input type="text" 
                           name="details" 
                           id="details" 
                           value="{{ old('details', $innovationStep->details) }}" 
                           placeholder="e.g. Hand-picked red cherries, 20°+ Brix sugar level, High altitude (1200m+)" 
                           class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl p-3 focus:bg-white focus:ring-2 focus:ring-slate-900 transition-all">
                    <p class="text-[11px] text-slate-400 mt-1">Separate multiple badges with commas. Displayed as highlighted tags beneath the description.</p>
                </div>
            </div>

            <!-- Indonesian Fields -->
            <div x-show="langTab === 'id'" class="space-y-5" style="display: none;">
                <div>
                    <label for="title_id" class="block text-xs font-semibold text-slate-700 mb-1">
                        Judul Langkah (Bahasa Indonesia)
                    </label>
                    <input type="text" 
                           name="title_id" 
                           id="title_id" 
                           value="{{ old('title_id', $innovationStep->title_id) }}" 
                           placeholder="Contoh: Pengadaan Ceri Etis" 
                           class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl p-3 focus:bg-white focus:ring-2 focus:ring-slate-900 transition-all font-medium">
                    @error('title_id')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description_id" class="block text-xs font-semibold text-slate-700 mb-1">
                        Deskripsi Langkah (Bahasa Indonesia)
                    </label>
                    <textarea name="description_id" 
                              id="description_id" 
                              rows="4" 
                              placeholder="Jelaskan detail proses teknis atau logistik pada langkah ini..." 
                              class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl p-3 focus:bg-white focus:ring-2 focus:ring-slate-900 transition-all">{{ old('description_id', $innovationStep->description_id) }}</textarea>
                    @error('description_id')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="details_id" class="block text-xs font-semibold text-slate-700 mb-1">
                        Detail Poin Kunci (Bahasa Indonesia) <span class="text-slate-400 font-normal">(Pisahkan dengan koma)</span>
                    </label>
                    <input type="text" 
                           name="details_id" 
                           id="details_id" 
                           value="{{ old('details_id', $innovationStep->details_id) }}" 
                           placeholder="Contoh: Petik tangan ceri merah, Kadar gula 20°+ Brix, Dataran tinggi (1200m+)" 
                           class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl p-3 focus:bg-white focus:ring-2 focus:ring-slate-900 transition-all">
                </div>
            </div>

            <!-- Common Config (Image, Step Number, Order, Status) -->
            <div class="pt-5 border-t border-slate-100 space-y-5">
                <div>
                    <label for="image" class="block text-xs font-semibold text-slate-700 mb-1">
                        Step Feature Image URL
                    </label>
                    <input type="url" 
                           name="image" 
                           id="image" 
                           value="{{ old('image', $innovationStep->image) }}" 
                           placeholder="https://images.unsplash.com/... or /storage/..." 
                           class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl p-3 focus:bg-white focus:ring-2 focus:ring-slate-900 transition-all font-mono">
                    @if($innovationStep->image)
                        <div class="mt-2 flex items-center gap-3">
                            <img src="{{ $innovationStep->image }}" alt="Preview" class="w-16 h-12 rounded-lg object-cover border border-slate-200 bg-slate-100 shadow-2xs">
                            <span class="text-[11px] text-slate-500">Current step image preview</span>
                        </div>
                    @endif
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="step_number" class="block text-xs font-semibold text-slate-700 mb-1">
                            Step Badge Number <span class="text-slate-400 font-normal">(e.g. 01, 02)</span>
                        </label>
                        <input type="text" 
                               name="step_number" 
                               id="step_number" 
                               value="{{ old('step_number', $innovationStep->step_number) }}" 
                               placeholder="01" 
                               maxlength="10" 
                               class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl p-3 focus:bg-white focus:ring-2 focus:ring-slate-900 transition-all font-mono">
                    </div>

                    <div>
                        <label for="order" class="block text-xs font-semibold text-slate-700 mb-1">
                            Display Order Sequence <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" 
                               name="order" 
                               id="order" 
                               value="{{ old('order', $innovationStep->order) }}" 
                               required 
                               min="1" 
                               class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl p-3 focus:bg-white focus:ring-2 focus:ring-slate-900 transition-all font-mono">
                    </div>
                </div>

                <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-900 block">Publish & Activate Step</span>
                        <span class="text-[11px] text-slate-500">Active steps are immediately visible on the /innovation landing page.</span>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $innovationStep->is_active) ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-hidden rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                    </label>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.innovation-steps.index') }}" class="btn btn-secondary text-xs py-2.5 px-4">Cancel</a>
            <button type="submit" class="btn btn-primary text-xs py-2.5 px-6 font-bold shadow-xs">
                Update Process Step
            </button>
        </div>
    </form>
</div>
@endsection
