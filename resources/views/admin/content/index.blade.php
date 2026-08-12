@extends('admin.layouts.app')
@section('title', 'Content')
@section('page_title', 'Content Management')

@section('content')
<div class="card-modern">
    <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
        <div>
            <h3 class="text-base font-semibold text-slate-900">Website Pages</h3>
            <p class="text-xs text-slate-500">Manage dynamic landing pages, content blocks, and page versions.</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="text-xs font-medium px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 border border-slate-200">{{ count($pages) }} Pages Total</span>
            <a href="{{ route('admin.content.create') }}" class="btn btn-primary py-1.5 px-3.5 text-xs shadow-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Create New Page
            </a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>Page Title</th>
                    <th>URL Slug</th>
                    <th>Status</th>
                    <th>Content Blocks</th>
                    <th>Last Updated</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pages as $page)
                <tr>
                    <td class="font-semibold text-slate-900">{{ $page->title }}</td>
                    <td class="font-mono text-xs text-indigo-600">/{{ $page->slug }}</td>
                    <td>
                        @if ($page->is_published)
                            <span class="badge badge-published">Published</span>
                        @else
                            <span class="badge badge-draft">Draft</span>
                        @endif
                    </td>
                    <td class="text-xs font-medium text-slate-600">{{ $page->blocks->count() }} Block(s)</td>
                    <td class="text-xs text-slate-500 font-mono">{{ $page->updated_at->diffForHumans() }}</td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.content.edit', $page) }}" class="btn btn-secondary py-1.5 px-3 text-xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit
                            </a>
                            <form action="{{ route('admin.content.destroy', $page) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this page and all its content blocks?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-secondary text-rose-600 hover:text-rose-700 hover:bg-rose-50 border-rose-200 py-1.5 px-2.5 text-xs font-semibold">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-10 text-slate-400">
                        No pages found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection