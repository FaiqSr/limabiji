@extends('admin.layouts.app')
@section('title', 'Dashboard Console')
@section('page_title', 'Operations & Content Console')

@section('content')
<div class="space-y-6">

    <!-- FOCAL POINT: Primary Decision & Action Bar (Article Approval & Publication Pipeline) -->
    <div class="bg-white rounded-lg border border-slate-200 p-5 shadow-2xs">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-lg bg-slate-100 text-slate-700 border border-slate-200 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-base font-semibold text-slate-900">Content Publication Pipeline</h2>
                        @if($stats['pending_articles'] > 0)
                            <span class="px-2.5 py-0.5 rounded-lg text-xs font-medium bg-amber-50 text-amber-800 border border-amber-200 animate-pulse">
                                {{ $stats['pending_articles'] }} Pending Review
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-lg text-xs font-medium bg-emerald-50 text-emerald-800 border border-emerald-200">
                                All Clear
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-slate-500 mt-1">
                        @if($stats['pending_articles'] > 0)
                            Articles require editor review before publishing to international live sites.
                        @else
                            No pending article submissions. All published stories are up to date.
                        @endif
                    </p>
                </div>
            </div>

            <!-- Quick Action Command Group -->
            <div class="flex flex-wrap items-center gap-2">
                @if (auth()->user()?->role === 'admin' && $stats['pending_articles'] > 0)
                    <a href="{{ route('admin.approvals.index') }}" class="btn btn-primary text-xs py-2 px-3.5 shadow-2xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Review Pending ({{ $stats['pending_articles'] }})
                    </a>
                @endif
                <a href="{{ route('admin.news.create') }}" class="btn btn-secondary text-xs py-2 px-3">
                    + New Article
                </a>
                <a href="{{ route('admin.content.create') }}" class="btn btn-secondary text-xs py-2 px-3">
                    + New Page
                </a>
            </div>
        </div>

        @if(isset($pendingArticlesList) && count($pendingArticlesList) > 0)
            <div class="mt-4 pt-4 border-t border-slate-100 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach($pendingArticlesList as $pendingItem)
                    <div class="p-3 rounded-lg bg-slate-50 border border-slate-200 flex items-center justify-between gap-3">
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-semibold text-slate-900 truncate">{{ $pendingItem->title }}</p>
                            <div class="flex items-center gap-2 text-[11px] text-slate-500 font-mono mt-0.5">
                                <span>{{ $pendingItem->author?->name ?? 'Editor' }}</span>
                                <span>·</span>
                                <span>{{ $pendingItem->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        @if (auth()->user()?->role === 'admin')
                            <a href="{{ route('admin.approvals.index') }}" class="text-xs font-semibold text-slate-900 hover:underline shrink-0">Review &rarr;</a>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- CORE KPI METRICS ROW -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg border border-slate-200 p-4 shadow-2xs">
            <span class="text-xs font-medium text-slate-500 uppercase tracking-wider block">CMS Pages</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="font-mono text-2xl font-bold text-slate-900 tracking-tight">{{ number_format($stats['pages_count']) }}</span>
                <span class="text-[11px] font-mono font-medium px-2 py-0.5 rounded-lg bg-slate-100 text-slate-700 border border-slate-200">ACTIVE</span>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-slate-200 p-4 shadow-2xs">
            <span class="text-xs font-medium text-slate-500 uppercase tracking-wider block">Articles Published</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="font-mono text-2xl font-bold text-slate-900 tracking-tight">{{ number_format($stats['articles_count']) }}</span>
                <span class="text-[11px] font-mono font-medium px-2 py-0.5 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200">LIVE</span>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-slate-200 p-4 shadow-2xs">
            <span class="text-xs font-medium text-slate-500 uppercase tracking-wider block">Coffee Origins</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="font-mono text-2xl font-bold text-slate-900 tracking-tight">{{ number_format($stats['origins_count']) }}</span>
                <span class="text-[11px] font-mono font-medium px-2 py-0.5 rounded-lg bg-slate-100 text-slate-700 border border-slate-200">REGIONS</span>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-slate-200 p-4 shadow-2xs">
            <span class="text-xs font-medium text-slate-500 uppercase tracking-wider block">Page Views (7D)</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="font-mono text-2xl font-bold text-slate-900 tracking-tight">{{ number_format($stats['page_views_7d']) }}</span>
                <span class="text-[11px] font-mono font-medium px-2 py-0.5 rounded-lg bg-slate-100 text-slate-700 border border-slate-200">{{ number_format($stats['unique_visitors_7d']) }} UNIQUE</span>
            </div>
        </div>
    </div>

    <!-- SIGNATURE ELEMENT: Agritech Origin & Cupping Score Matrix -->
    <div class="bg-white rounded-lg border border-slate-200 p-5 shadow-2xs">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
            <div>
                <h3 class="text-base font-semibold text-slate-900">Agritech Coffee Origins Matrix</h3>
                <p class="text-xs text-slate-500 mt-0.5">Specialty civet coffee micro-lot profiles & cupping scores.</p>
            </div>
            <a href="{{ route('admin.origins.index') }}" class="text-xs font-semibold text-slate-700 hover:text-slate-900 flex items-center gap-1">
                Manage Origins &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Origin Name</th>
                        <th>Province</th>
                        <th>Cupping Score</th>
                        <th>Altitude</th>
                        <th>Fermentation Process</th>
                        <th>Harvest Window</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($origins as $origin)
                    <tr>
                        <td class="font-semibold text-slate-900">
                            <a href="{{ route('admin.origins.edit', $origin) }}" class="hover:text-slate-600 transition-colors">
                                {{ $origin->name }}
                            </a>
                        </td>
                        <td class="text-xs text-slate-600 font-medium">{{ $origin->province }}</td>
                        <td>
                            <span class="font-mono text-xs font-semibold px-2.5 py-0.5 rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-200">
                                ⭐ {{ $origin->score ?: '85+' }}
                            </span>
                        </td>
                        <td class="font-mono text-xs text-slate-600">{{ $origin->altitude }}</td>
                        <td class="text-xs text-slate-700 font-medium">{{ $origin->process }}</td>
                        <td class="font-mono text-xs text-slate-500">{{ $origin->harvest }}</td>
                        <td>
                            @if ($origin->is_active)
                                <span class="inline-flex items-center gap-1 text-[11px] font-medium px-2 py-0.5 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    ● Active Lot
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-[11px] font-medium px-2 py-0.5 rounded-lg bg-slate-100 text-slate-500 border border-slate-200">
                                    ○ Inactive
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-8 text-slate-400 text-xs">
                            No coffee origins registered in matrix.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- BOTTOM GRID: Recent News Articles & Top Page Traffic -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Articles Feed -->
        <div class="bg-white rounded-lg border border-slate-200 p-5 shadow-2xs">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                <h3 class="text-base font-semibold text-slate-900">Recent News & Stories</h3>
                <a href="{{ route('admin.news.index') }}" class="text-xs font-semibold text-slate-700 hover:text-slate-900">View All News &rarr;</a>
            </div>
            <div class="space-y-3">
                @forelse($recentArticles as $recent)
                <div class="p-3 rounded-lg bg-slate-50 border border-slate-200 flex items-center justify-between gap-3 hover:border-slate-300 transition-all">
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="badge badge-{{ $recent->status }} text-[10px]">{{ ucfirst($recent->status) }}</span>
                            <span class="text-[11px] text-slate-400 font-mono">{{ $recent->category }}</span>
                        </div>
                        <p class="text-xs font-semibold text-slate-900 truncate">{{ $recent->title }}</p>
                        @if($recent->title_id)
                            <p class="text-[11px] text-slate-500 truncate">🇮🇩 {{ $recent->title_id }}</p>
                        @endif
                    </div>
                    <a href="{{ route('admin.news.edit', $recent) }}" class="btn btn-secondary py-1 px-2.5 text-xs">Edit</a>
                </div>
                @empty
                <p class="text-xs text-slate-400 text-center py-6">No articles published yet.</p>
                @endforelse
            </div>
        </div>

        <!-- Top Pages Analytics Matrix -->
        <div class="bg-white rounded-lg border border-slate-200 p-5 shadow-2xs">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                <h3 class="text-base font-semibold text-slate-900">Top Pages (Last 7 Days)</h3>
                <a href="{{ route('admin.analytics.index') }}" class="text-xs font-semibold text-slate-700 hover:text-slate-900">Full Analytics &rarr;</a>
            </div>
            <div class="space-y-2.5">
                @forelse($stats['top_pages'] as $pageItem)
                <div class="p-3 rounded-lg bg-slate-50 border border-slate-200 flex items-center justify-between font-mono text-xs">
                    <span class="text-slate-800 font-semibold truncate">/{{ $pageItem['page'] ?? '' }}</span>
                    <span class="px-2.5 py-0.5 rounded-lg bg-slate-100 text-slate-700 font-semibold border border-slate-200 shrink-0">
                        {{ number_format($pageItem['views'] ?? 0) }} views
                    </span>
                </div>
                @empty
                <div class="p-6 text-center text-xs text-slate-400">
                    No analytics event recorded in past 7 days.
                </div>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection