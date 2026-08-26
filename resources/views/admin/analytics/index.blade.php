@extends('admin.layouts.app')
@section('title', 'Analytics')
@section('page_title', 'Analytics Overview')

@section('content')
<!-- KPI Header Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
    <div class="stat-card">
        <div>
            <span class="stat-label">Total Page Views ({{ $days }}d)</span>
            <p class="stat-value mt-1">{{ number_format($stats['total_views']) }}</p>
        </div>
        <div class="w-10 h-10 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center shrink-0 border border-slate-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
        </div>
    </div>

    <div class="stat-card">
        <div>
            <span class="stat-label">Unique Visitors ({{ $days }}d)</span>
            <p class="stat-value mt-1">{{ number_format($stats['unique_visitors']) }}</p>
        </div>
        <div class="w-10 h-10 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center shrink-0 border border-slate-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
        </div>
    </div>

    <div class="stat-card">
        <div>
            <span class="stat-label">Active Tracked Pages</span>
            <p class="stat-value mt-1">{{ count($stats['top_pages']) }}</p>
        </div>
        <div class="w-10 h-10 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center shrink-0 border border-slate-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
        </div>
    </div>
</div>

<!-- Main Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Top Pages Card -->
    <div class="card-modern">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
            <h3 class="text-base font-semibold text-slate-900">Top Visited Pages</h3>
            <span class="text-xs text-slate-500 font-mono">Sorted by Views</span>
        </div>
        <div class="overflow-x-auto">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Page Path</th>
                        <th class="text-right">Total Views</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($stats['top_pages'] as $page)
                    <tr>
                        <td class="font-mono text-sm text-slate-900 font-medium">/{{ $page['page'] }}</td>
                        <td class="text-right font-semibold text-slate-900">{{ number_format($page['views']) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" class="text-center py-6 text-slate-400">No traffic data recorded yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Daily Views Chart Bar Card -->
    <div class="card-modern">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
            <h3 class="text-base font-semibold text-slate-900">Daily View Trends</h3>
            <span class="text-xs text-slate-500 font-mono">Last 14 Days</span>
        </div>
        <div class="space-y-3">
            @php $maxCount = max(array_column($stats['daily_views'], 'count') ?: [1]); @endphp
            @foreach (array_slice($stats['daily_views'], -14) as $day)
            <div class="flex items-center justify-between gap-3">
                <span class="text-xs font-mono text-slate-500 w-24 shrink-0">{{ $day['date'] }}</span>
                <div class="flex-1 bg-slate-100 h-2.5 rounded-lg overflow-hidden flex items-center">
                    <div class="h-full bg-slate-800 rounded-lg transition-all duration-300" style="width: {{ $maxCount > 0 ? max(5, round(($day['count'] / $maxCount) * 100)) : 5 }}%"></div>
                </div>
                <span class="text-xs font-semibold text-slate-700 w-10 text-right">{{ $day['count'] }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection