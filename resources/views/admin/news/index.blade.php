@extends('admin.layouts.app')
@section('title', 'News')
@section('page_title', 'News & Articles')

@section('content')
<div class="card-modern">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-5 pb-4 border-b border-slate-100">
        <!-- Status & Language Filter Pills -->
        <div class="flex flex-wrap items-center gap-3">
            <div class="flex flex-wrap gap-1 bg-slate-100 p-1 rounded-lg border border-slate-200">
                <a href="{{ route('admin.news.index', array_merge(request()->except('status', 'page'))) }}"
                   class="px-2.5 py-1 text-xs font-semibold rounded-lg transition-all {{ !request('status') ? 'bg-white text-indigo-600 shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
                    All Status
                </a>
                <a href="{{ route('admin.news.index', array_merge(request()->except('page'), ['status' => 'draft'])) }}"
                   class="px-2.5 py-1 text-xs font-semibold rounded-lg transition-all {{ request('status') === 'draft' ? 'bg-white text-indigo-600 shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
                    Drafts
                </a>
                <a href="{{ route('admin.news.index', array_merge(request()->except('page'), ['status' => 'pending'])) }}"
                   class="px-2.5 py-1 text-xs font-semibold rounded-lg transition-all {{ request('status') === 'pending' ? 'bg-white text-indigo-600 shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
                    Pending Review
                </a>
                <a href="{{ route('admin.news.index', array_merge(request()->except('page'), ['status' => 'published'])) }}"
                   class="px-2.5 py-1 text-xs font-semibold rounded-lg transition-all {{ request('status') === 'published' ? 'bg-white text-indigo-600 shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
                    Published
                </a>
            </div>

            <div class="flex flex-wrap gap-1 bg-slate-100 p-1 rounded-lg border border-slate-200">
                <a href="{{ route('admin.news.index', array_merge(request()->except('lang', 'page'))) }}"
                   class="px-2.5 py-1 text-xs font-semibold rounded-lg transition-all {{ !request('lang') ? 'bg-white text-emerald-600 shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
                    🌐 All Languages
                </a>
                <a href="{{ route('admin.news.index', array_merge(request()->except('page'), ['lang' => 'en'])) }}"
                   class="px-2.5 py-1 text-xs font-semibold rounded-lg transition-all {{ request('lang') === 'en' ? 'bg-white text-indigo-600 shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
                    🇬🇧 English (EN)
                </a>
                <a href="{{ route('admin.news.index', array_merge(request()->except('page'), ['lang' => 'id'])) }}"
                   class="px-2.5 py-1 text-xs font-semibold rounded-lg transition-all {{ request('lang') === 'id' ? 'bg-white text-emerald-600 shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
                    🇮🇩 Bahasa (ID)
                </a>
            </div>
        </div>

        <a href="{{ route('admin.news.create') }}" class="btn btn-primary text-xs py-2 px-4 shadow-xs self-start md:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Create New Article
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Language</th>
                    <th>Status</th>
                    <th>Author</th>
                    <th>Views</th>
                    <th>Created Date</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($articles as $article)
                <tr>
                    <td class="font-semibold text-slate-900">
                        <div>{{ $article->title }}</div>
                        @if ($article->title_id)
                            <div class="text-[11px] font-normal text-slate-400 mt-0.5">🇮🇩 {{ $article->title_id }}</div>
                        @endif
                    </td>
                    <td>
                        <span class="inline-block px-2.5 py-0.5 rounded-lg text-xs font-medium bg-slate-100 text-slate-700 border border-slate-200">
                            {{ $article->category }}
                        </span>
                    </td>
                    <td>
                        @php
                            $hasEn = !empty($article->content);
                            $hasId = !empty($article->content_id);
                        @endphp
                        @if ($hasEn && $hasId)
                            <span class="inline-flex items-center gap-1 text-[11px] font-medium px-2 py-0.5 rounded-lg bg-slate-100 text-slate-800 border border-slate-200">
                                🇬🇧 EN + 🇮🇩 ID
                            </span>
                        @elseif ($hasEn)
                            <span class="inline-flex items-center gap-1 text-[11px] font-medium px-2 py-0.5 rounded-lg bg-slate-100 text-slate-800 border border-slate-200">
                                🇬🇧 EN
                            </span>
                        @elseif ($hasId)
                            <span class="inline-flex items-center gap-1 text-[11px] font-medium px-2 py-0.5 rounded-lg bg-slate-100 text-slate-800 border border-slate-200">
                                🇮🇩 ID
                            </span>
                        @else
                            <span class="text-xs text-slate-400 font-mono">—</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge badge-{{ $article->status }}">{{ ucfirst($article->status) }}</span>
                    </td>
                    <td class="text-xs text-slate-600 font-medium">{{ $article->author?->name ?? '—' }}</td>
                    <td class="text-xs text-slate-500">
                        <span class="inline-flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            {{ number_format($article->views) }}
                        </span>
                    </td>
                    <td class="text-xs text-slate-500 font-mono">{{ $article->created_at->format('d M Y') }}</td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.news.edit', $article) }}" class="btn btn-secondary py-1.5 px-3 text-xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit
                            </a>
                            <form action="{{ route('admin.news.destroy', $article) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this article?')">
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
                    <td colspan="7" class="text-center py-12 text-slate-400">
                        <p class="text-sm font-medium">No articles found for the selected filter.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection