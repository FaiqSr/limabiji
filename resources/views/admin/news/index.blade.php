@extends('admin.layouts.app')
@section('title', 'News')
@section('page_title', 'News & Articles Management')

@section('content')
<div class="space-y-6">
    <!-- Header Toolbar: Search, Filters & Actions -->
    <div class="card-modern space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <!-- Search Form Input -->
            <form method="GET" action="{{ route('admin.news.index') }}" class="flex-1  relative">
                @if (request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                @if (request('lang'))
                    <input type="hidden" name="lang" value="{{ request('lang') }}">
                @endif

                <div class="relative">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Search articles by title, category, or snippet..." 
                           class="w-full pl-9 pr-8 text-xs bg-slate-50 border border-slate-200 rounded-xl p-2.5 focus:bg-white focus:ring-2 focus:ring-indigo-500 transition-all">
                    
                    @if (request('search'))
                        <a href="{{ route('admin.news.index', request()->except('search', 'page')) }}" 
                           class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600"
                           title="Clear search">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </a>
                    @endif
                </div>
            </form>

            <!-- Action Buttons -->
            <div class="grid grid-cols-2 items-center gap-2">
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary text-xs py-2 px-3 shadow-2xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    <span>Manage Categories</span>
                </a>

                <!-- Create Article CTA Button -->
                <a href="{{ route('admin.news.create') }}" class="btn btn-primary text-xs py-2 px-4 shadow-2xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Create News</span>
                </a>
            </div>
        </div>

        <!-- Filter Pills Bar -->
        <div class="flex flex-wrap items-center justify-between gap-3 pt-3 border-t border-slate-100">
            <div class="flex flex-wrap items-center gap-3">
                <!-- Status Filters -->
                <div class="flex flex-wrap gap-1 bg-slate-100 p-1 rounded-xl border border-slate-200">
                    <a href="{{ route('admin.news.index', array_merge(request()->except('status', 'page'))) }}"
                       class="px-3 py-1 text-xs font-semibold rounded-lg transition-all {{ !request('status') ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
                        All Status
                    </a>
                    <a href="{{ route('admin.news.index', array_merge(request()->except('page'), ['status' => 'draft'])) }}"
                       class="px-3 py-1 text-xs font-semibold rounded-lg transition-all {{ request('status') === 'draft' ? 'bg-white text-amber-700 shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
                        Drafts
                    </a>
                    <a href="{{ route('admin.news.index', array_merge(request()->except('page'), ['status' => 'published'])) }}"
                       class="px-3 py-1 text-xs font-semibold rounded-lg transition-all {{ request('status') === 'published' ? 'bg-white text-emerald-700 shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
                        Published
                    </a>
                </div>

                <!-- Language Filters -->
                <div class="flex flex-wrap gap-1 bg-slate-100 p-1 rounded-xl border border-slate-200">
                    <a href="{{ route('admin.news.index', array_merge(request()->except('lang', 'page'))) }}"
                       class="px-3 py-1 text-xs font-semibold rounded-lg transition-all {{ !request('lang') ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
                        🌐 All Languages
                    </a>
                    <a href="{{ route('admin.news.index', array_merge(request()->except('page'), ['lang' => 'en'])) }}"
                       class="px-3 py-1 text-xs font-semibold rounded-lg transition-all {{ request('lang') === 'en' ? 'bg-white text-indigo-600 shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
                        🇬🇧 English
                    </a>
                    <a href="{{ route('admin.news.index', array_merge(request()->except('page'), ['lang' => 'id'])) }}"
                       class="px-3 py-1 text-xs font-semibold rounded-lg transition-all {{ request('lang') === 'id' ? 'bg-white text-emerald-600 shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
                        🇮🇩 Bahasa
                    </a>
                </div>
            </div>

            <!-- Total Results Counter -->
            <div class="text-xs text-slate-500 font-medium">
                Showing <span class="font-bold text-slate-800">{{ $articles->total() }}</span> {{ Str::plural('article', $articles->total()) }}
            </div>
        </div>

        @if (request('search'))
            <div class="flex items-center gap-2 pt-1 text-xs text-slate-600 bg-indigo-50/70 border border-indigo-100 rounded-xl px-3 py-2">
                <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <span>Searching results for <strong>"{{ request('search') }}"</strong></span>
                <a href="{{ route('admin.news.index', request()->except('search', 'page')) }}" 
                   class="text-indigo-600 hover:text-indigo-800 font-semibold underline ml-auto text-[11px]">
                    Clear Search
                </a>
            </div>
        @endif
    </div>

    <!-- Articles Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        @forelse ($articles as $article)
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-2xs hover:shadow-md hover:border-slate-300 transition-all duration-200 flex flex-col group">
                <!-- Cover Image & Overlay Tags -->
                <div class="relative aspect-16/9 bg-slate-100 overflow-hidden">
                    @if ($article->image)
                        <img src="{{ $article->image }}" 
                             alt="{{ $article->title }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-slate-400 gap-1 bg-slate-50">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                            </svg>
                            <span class="text-[11px] font-medium">No cover image</span>
                        </div>
                    @endif

                    <!-- Top Gradient -->
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-black/20 pointer-events-none"></div>

                    <!-- Top Left: Category Badges -->
                    <div class="absolute top-3 left-3 flex flex-wrap gap-1 max-w-[70%]">
                        @if ($article->categories->isNotEmpty())
                            @foreach ($article->categories as $cat)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-white/95 text-slate-800 shadow-xs backdrop-blur-xs border border-white/30">
                                    {{ $cat->getNameForLocale() }}
                                </span>
                            @endforeach
                        @elseif ($article->category)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-white/95 text-slate-800 shadow-xs backdrop-blur-xs border border-white/30">
                                {{ $article->category }}
                            </span>
                        @endif
                    </div>

                    <!-- Top Right: Status Badge -->
                    <div class="absolute top-3 right-3">
                        @if ($article->status === 'published')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-500/90 text-white shadow-xs backdrop-blur-xs">
                                <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                                Published
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-500/90 text-white shadow-xs backdrop-blur-xs">
                                <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                                Draft
                            </span>
                        @endif
                    </div>

                    <!-- Bottom Left: Language Support Badges -->
                    @php
                        $hasEn = !empty($article->content);
                        $hasId = !empty($article->content_id);
                    @endphp
                    <div class="absolute bottom-3 left-3 flex items-center gap-1.5">
                        @if ($hasEn && $hasId)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-900/80 text-white backdrop-blur-xs">
                                🇬🇧 EN + 🇮🇩 ID
                            </span>
                        @elseif ($hasEn)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-900/80 text-white backdrop-blur-xs">
                                🇬🇧 EN
                            </span>
                        @elseif ($hasId)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-900/80 text-white backdrop-blur-xs">
                                🇮🇩 ID
                            </span>
                        @endif
                    </div>

                    <!-- Bottom Right: Views Counter -->
                    <div class="absolute bottom-3 right-3">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-semibold bg-slate-900/80 text-white backdrop-blur-xs">
                            <svg class="w-3 h-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <span>{{ number_format($article->views) }}</span>
                        </span>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                    <div class="space-y-2">
                        <!-- Titles -->
                        <div>
                            <h4 class="text-base font-bold text-slate-900 group-hover:text-indigo-600 transition-colors line-clamp-2 leading-snug">
                                {{ $article->title }}
                            </h4>
                            @if ($article->title_id)
                                <p class="text-xs text-slate-500 mt-1 line-clamp-1 flex items-center gap-1">
                                    <span class="text-[10px] font-semibold text-slate-400">🇮🇩</span>
                                    <span>{{ $article->title_id }}</span>
                                </p>
                            @endif
                        </div>

                        <!-- Excerpt -->
                        @php $excerpt = $article->getExcerptForLocale('en'); @endphp
                        @if ($excerpt)
                            <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed pt-1">
                                {{ $excerpt }}
                            </p>
                        @endif
                    </div>

                    <!-- Meta & Actions Section -->
                    <div class="space-y-3 pt-3 border-t border-slate-100">
                        <!-- Author & Date -->
                        <div class="flex items-center justify-between text-xs text-slate-500">
                            <div class="flex items-center gap-1.5 font-medium truncate">
                                <div class="w-5 h-5 rounded-full bg-indigo-50 text-indigo-600 border border-indigo-100 flex items-center justify-center font-bold text-[10px]">
                                    {{ strtoupper(substr($article->author?->name ?? 'A', 0, 1)) }}
                                </div>
                                <span class="truncate">{{ $article->author?->name ?? 'Admin' }}</span>
                            </div>
                            <span class="font-mono text-[11px] text-slate-400 shrink-0">
                                {{ $article->created_at->format('d M Y') }}
                            </span>
                        </div>

                        <!-- Buttons Row -->
                        <div class="flex items-center justify-between gap-2 pt-1">
                            @if ($article->status === 'published')
                                <a href="{{ route('landingpages.news.detail', $article->slug) }}" 
                                   target="_blank" 
                                   class="text-xs text-slate-500 hover:text-indigo-600 font-medium inline-flex items-center gap-1 transition-colors">
                                    <span>View Article</span>
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                </a>
                            @else
                                <span class="text-[11px] text-amber-600 font-medium italic">Unpublished draft</span>
                            @endif

                            <div class="flex items-center gap-1.5">
                                <a href="{{ route('admin.news.edit', $article) }}" 
                                   class="btn btn-secondary py-1.5 px-3 text-xs font-semibold">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    <span>Edit</span>
                                </a>

                                <form action="{{ route('admin.news.destroy', $article) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Are you sure you want to delete this article?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 border border-transparent hover:border-rose-200 transition-colors"
                                            title="Delete Article"
                                            aria-label="Delete {{ $article->title }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full card-modern text-center py-16">
                <div class="w-14 h-14 mx-auto rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-3">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                </div>
                <h4 class="text-sm font-bold text-slate-800">No Articles Found</h4>
                <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                    @if (request('search'))
                        No articles match the keyword "{{ request('search') }}".
                    @else
                        No news articles match the selected status or language filters.
                    @endif
                </p>
                <div class="mt-4 flex items-center justify-center gap-2">
                    <a href="{{ route('admin.news.index') }}" class="btn btn-secondary text-xs py-2 px-4">
                        Reset Filters
                    </a>
                    <a href="{{ route('admin.news.create') }}" class="btn btn-primary text-xs py-2 px-4 shadow-2xs inline-flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Create Article</span>
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination Component -->
    <x-admin.pagination :paginator="$articles" item-name="articles" />
</div>
@endsection