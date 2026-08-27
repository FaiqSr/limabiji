@extends('admin.layouts.app')
@section('title', 'Add New FAQ')
@section('page_title', 'Create FAQ Item')

@section('content')
<form action="{{ route('admin.faqs.store') }}" method="POST" x-data="{ locale: 'en' }" class="max-w-4xl mx-auto space-y-6">
    @csrf

    <div class="flex items-center justify-between">
        <a href="{{ route('admin.faqs.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-600 hover:text-slate-900 bg-white border border-slate-200 px-3.5 py-2 rounded-xl shadow-2xs hover:bg-slate-50 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Back to FAQs</span>
        </a>

        <!-- Language Switcher Tabs -->
        <div class="inline-flex bg-slate-100 p-1 rounded-xl border border-slate-200">
            <button type="button" @click="locale = 'en'" :class="locale === 'en' ? 'bg-white text-emerald-700 shadow-2xs font-semibold' : 'text-slate-600 hover:text-slate-900'" class="px-3 py-1.5 text-xs rounded-lg transition-all flex items-center gap-1.5">
                <span>🇺🇸 English (EN)</span>
            </button>
            <button type="button" @click="locale = 'id'" :class="locale === 'id' ? 'bg-white text-emerald-700 shadow-2xs font-semibold' : 'text-slate-600 hover:text-slate-900'" class="px-3 py-1.5 text-xs rounded-lg transition-all flex items-center gap-1.5">
                <span>🇮🇩 Indonesia (ID)</span>
            </button>
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="card-modern space-y-6">
        <!-- English Section -->
        <div x-show="locale === 'en'" class="space-y-4">
            <div>
                <label for="question" class="block text-xs font-semibold text-slate-700 mb-1.5">
                    Question (English) <span class="text-rose-500">*</span>
                </label>
                <input type="text" 
                       name="question" 
                       id="question" 
                       value="{{ old('question', $faq->question) }}" 
                       required 
                       placeholder="e.g. How does your enzymatic civet coffee process work?" 
                       class="w-full text-xs bg-white border @error('question') border-rose-500 @else border-slate-200 @enderror rounded-xl p-3 focus:ring-2 focus:ring-emerald-500 transition-all">
                @error('question')
                    <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="answer" class="block text-xs font-semibold text-slate-700 mb-1.5">
                    Answer (English) <span class="text-rose-500">*</span>
                </label>
                <textarea name="answer" 
                          id="answer" 
                          rows="6" 
                          required 
                          placeholder="Provide detailed answer in English..." 
                          class="w-full text-xs bg-white border @error('answer') border-rose-500 @else border-slate-200 @enderror rounded-xl p-3 focus:ring-2 focus:ring-emerald-500 leading-relaxed transition-all">{{ old('answer', $faq->answer) }}</textarea>
                @error('answer')
                    <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Indonesian Section -->
        <div x-show="locale === 'id'" style="display: none;" class="space-y-4">
            <div>
                <label for="question_id" class="block text-xs font-semibold text-slate-700 mb-1.5">
                    Pertanyaan (Bahasa Indonesia)
                </label>
                <input type="text" 
                       name="question_id" 
                       id="question_id" 
                       value="{{ old('question_id', $faq->question_id) }}" 
                       placeholder="e.g. Bagaimana proses kopi luwak enzimatik Anda bekerja tanpa hewan?" 
                       class="w-full text-xs bg-white border @error('question_id') border-rose-500 @else border-slate-200 @enderror rounded-xl p-3 focus:ring-2 focus:ring-emerald-500 transition-all">
                @error('question_id')
                    <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="answer_id" class="block text-xs font-semibold text-slate-700 mb-1.5">
                    Jawaban (Bahasa Indonesia)
                </label>
                <textarea name="answer_id" 
                          id="answer_id" 
                          rows="6" 
                          placeholder="Tuliskan jawaban lengkap dalam Bahasa Indonesia..." 
                          class="w-full text-xs bg-white border @error('answer_id') border-rose-500 @else border-slate-200 @enderror rounded-xl p-3 focus:ring-2 focus:ring-emerald-500 leading-relaxed transition-all">{{ old('answer_id', $faq->answer_id) }}</textarea>
                @error('answer_id')
                    <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100 grid grid-cols-1 sm:grid-cols-2 gap-4 items-center">
            <!-- Display Order -->
            <div>
                <label for="order" class="block text-xs font-semibold text-slate-700 mb-1.5">Display Index Order</label>
                <input type="number" 
                       name="order" 
                       id="order" 
                       value="{{ old('order', $faq->order) }}" 
                       min="0" 
                       class="w-full text-xs bg-white border @error('order') border-rose-500 @else border-slate-200 @enderror rounded-xl p-2.5 focus:ring-2 focus:ring-emerald-500">
                <p class="text-[11px] text-slate-400 mt-1">Lower numbers appear first on the landing page.</p>
                @error('order')
                    <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Active Status -->
            <div class="sm:pt-3">
                <label class="flex items-center gap-3 cursor-pointer p-3 rounded-xl bg-slate-50 border border-slate-200 hover:bg-slate-100 transition-colors">
                    <input type="checkbox" 
                           name="is_active" 
                           value="1" 
                           {{ old('is_active', $faq->is_active) ? 'checked' : '' }} 
                           class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 border-slate-300">
                    <div>
                        <span class="text-xs font-semibold text-slate-900 block">Publish on Landing Page</span>
                        <span class="text-[11px] text-slate-500">Active questions are visible to visitors.</span>
                    </div>
                </label>
            </div>
        </div>

        <div class="pt-2">
            <button type="submit" class="btn btn-primary w-full py-3 text-sm font-semibold shadow-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>Save FAQ Item</span>
            </button>
        </div>
    </div>
</form>
@endsection
