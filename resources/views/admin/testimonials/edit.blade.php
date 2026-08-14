@extends('admin.layouts.app')
@php $isCreating = !$testimonial->exists; @endphp
@section('title', $isCreating ? 'New Testimonial' : 'Edit Testimonial')
@section('page_title', $isCreating ? 'Create Client Testimonial' : 'Edit Testimonial: ' . $testimonial->name)

@section('content')
<form action="{{ $isCreating ? route('admin.testimonials.store') : route('admin.testimonials.update', $testimonial) }}" method="POST" x-data="{ locale: 'en' }">
    @csrf
    @if (!$isCreating)
        @method('PUT')
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Client Details Card -->
        <div class="card-modern space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-base font-semibold text-slate-900">Client Details</h3>
                <a href="{{ route('admin.testimonials.index') }}" class="text-xs text-slate-500 hover:text-indigo-600 font-medium flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Testimonials
                </a>
            </div>

            <div>
                <label for="name" class="block text-xs font-semibold text-slate-700 mb-1">Client / Reviewer Name <span class="text-rose-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name', $testimonial->name) }}" required placeholder="e.g. Michael Tan" class="w-full text-xs bg-white border @error('name') border-rose-500 @else border-slate-200 @enderror rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500">
                @error('name')
                    <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="company" class="block text-xs font-semibold text-slate-700 mb-1">Company / Role Title</label>
                <input type="text" name="company" id="company" value="{{ old('company', $testimonial->company) }}" placeholder="e.g. Founder at Artisan Coffee Co." class="w-full text-xs bg-white border @error('company') border-rose-500 @else border-slate-200 @enderror rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500">
                @error('company')
                    <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="order" class="block text-xs font-semibold text-slate-700 mb-1">Display Index Order</label>
                <input type="number" name="order" id="order" value="{{ old('order', $testimonial->order ?: 0) }}" min="0" class="w-full text-xs bg-white border @error('order') border-rose-500 @else border-slate-200 @enderror rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500">
                @error('order')
                    <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-2">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" {{ $testimonial->is_featured ? 'checked' : '' }} class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300">
                    <span class="text-xs font-medium text-slate-700">Feature on Homepage Showcase</span>
                </label>
            </div>
        </div>

        <!-- Review Content & Save Card -->
        <div class="card-modern space-y-4 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                    <h3 class="text-base font-semibold text-slate-900">Review Quote Content</h3>
                    <div class="inline-flex bg-slate-100 p-0.5 rounded-lg border border-slate-200">
                        <button type="button" @click="locale = 'en'" :class="locale === 'en' ? 'bg-white text-indigo-600 shadow-2xs font-semibold' : 'text-slate-600 hover:text-slate-900'" class="px-2.5 py-1 text-xs rounded-md transition-all">English (EN)</button>
                        <button type="button" @click="locale = 'id'" :class="locale === 'id' ? 'bg-white text-indigo-600 shadow-2xs font-semibold' : 'text-slate-600 hover:text-slate-900'" class="px-2.5 py-1 text-xs rounded-md transition-all">Bahasa (ID)</button>
                    </div>
                </div>

                <div x-show="locale === 'en'">
                    <label for="content" class="block text-xs font-semibold text-slate-700 mb-1">Review Quote (English) <span class="text-rose-500">*</span></label>
                    <textarea name="content" id="content" rows="6" required placeholder="Enter feedback quote from client..." class="w-full text-xs bg-white border @error('content') border-rose-500 @else border-slate-200 @enderror rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 leading-relaxed">{{ old('content', $testimonial->content) }}</textarea>
                    @error('content')
                        <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>
                <div x-show="locale === 'id'" style="display: none;">
                    <label for="content_id" class="block text-xs font-semibold text-slate-700 mb-1">Review Quote (Bahasa Indonesia)</label>
                    <textarea name="content_id" id="content_id" rows="6" placeholder="Tuliskan testimoni dalam Bahasa Indonesia..." class="w-full text-xs bg-white border @error('content_id') border-rose-500 @else border-slate-200 @enderror rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 leading-relaxed">{{ old('content_id', $testimonial->content_id) }}</textarea>
                    @error('content_id')
                        <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-full py-2.5 text-sm font-medium shadow-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Save Testimonial
            </button>
        </div>
    </div>
</form>
@endsection