@extends('admin.layouts.app')
@section('title', 'Create Page')
@section('page_title', 'Create New Page')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="card-modern">
        <div class="flex items-center justify-between mb-6 pb-3 border-b border-slate-100">
            <div>
                <h3 class="text-base font-semibold text-slate-900">Page Configuration</h3>
                <p class="text-xs text-slate-500">Set basic metadata to generate a new landing page.</p>
            </div>
            <a href="{{ route('admin.content.index') }}" class="btn btn-secondary py-1.5 px-3 text-xs">Back to List</a>
        </div>

        <form action="{{ route('admin.content.store') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Page Title <span class="text-rose-500">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}" required class="w-full text-xs bg-white border border-slate-200 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500" placeholder="e.g. Sustainability & Ethics">
                @error('title')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">URL Slug <span class="text-rose-500">*</span></label>
                <div class="flex items-center">
                    <span class="inline-flex items-center px-3 py-2 rounded-l-lg border border-r-0 border-slate-200 bg-slate-50 text-slate-500 text-xs font-mono">/</span>
                    <input type="text" name="slug" value="{{ old('slug') }}" required class="w-full text-xs bg-white border border-slate-200 rounded-r-lg p-2.5 focus:ring-2 focus:ring-indigo-500 font-mono" placeholder="sustainability">
                </div>
                @error('slug')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-2">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_published" value="1" {{ old('is_published') ? 'checked' : '' }} class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300">
                    <span class="text-xs font-medium text-slate-700">Publish immediately after creation</span>
                </label>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.content.index') }}" class="btn btn-secondary py-2 px-4 text-xs">Cancel</a>
                <button type="submit" class="btn btn-primary py-2 px-5 text-xs shadow-xs">
                    Create Page & Build Blocks &rarr;
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
