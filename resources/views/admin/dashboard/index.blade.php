@extends('admin.layouts.app')
@section('title', 'Dashboard Console')
@section('page_title', 'Operations & Content Console')

@section('content')
<div class="space-y-6">

    @if (session('success'))
        <div class="p-4 rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-semibold">
            ✓ {{ session('success') }}
        </div>
    @endif

    <!-- FOCAL POINT: Quick Action & Operations Bar -->
    <div class="bg-white rounded-lg border border-slate-200 p-5 shadow-2xs">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-lg bg-slate-900 text-white border border-slate-800 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-semibold text-slate-900">Lima Biji Admin Operations Console</h2>
                    <p class="text-xs text-slate-500 mt-1">
                        Manage specialty coffee landing pages, news stories, regional coffee origins, and global distribution network.
                    </p>
                </div>
            </div>

            <!-- Quick Action Command Group -->
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.news.create') }}" class="btn btn-primary text-xs py-2 px-3.5 shadow-2xs">
                    + New Article
                </a>
                <a href="{{ route('admin.origins.create') }}" class="btn btn-secondary text-xs py-2 px-3">
                    + New Origin
                </a>
            </div>
        </div>
    </div>

    <!-- CORE KPI METRICS ROW -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
        <div class="bg-white rounded-lg border border-slate-200 p-4 shadow-2xs">
            <span class="text-xs font-medium text-slate-500 uppercase tracking-wider block">Export Destinations</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="font-mono text-2xl font-bold text-slate-900 tracking-tight">{{ number_format($stats['export_destinations_count']) }}</span>
                <span class="text-[11px] font-mono font-medium px-2 py-0.5 rounded-lg bg-slate-100 text-slate-700 border border-slate-200">GLOBAL</span>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-slate-200 p-4 shadow-2xs">
            <span class="text-xs font-medium text-slate-500 uppercase tracking-wider block">Published Articles</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="font-mono text-2xl font-bold text-slate-900 tracking-tight">{{ number_format($stats['articles_published']) }}</span>
                <span class="text-[11px] font-mono font-medium px-2 py-0.5 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200">{{ number_format($stats['articles_draft']) }} DRAFTS</span>
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
            <a href="{{ route('admin.messages.index') }}" class="block group">
                <span class="text-xs font-medium text-slate-500 uppercase tracking-wider block group-hover:text-indigo-600 transition-colors">Inquiries</span>
                <div class="flex items-baseline justify-between mt-2">
                    <span class="font-mono text-2xl font-bold text-slate-900 tracking-tight group-hover:text-indigo-600 transition-colors">{{ number_format($stats['messages_count']) }}</span>
                    @if ($stats['messages_unread'] > 0)
                        <span class="text-[11px] font-mono font-medium px-2 py-0.5 rounded-lg bg-indigo-50 text-indigo-700 border border-indigo-200">{{ number_format($stats['messages_unread']) }} NEW</span>
                    @else
                        <span class="text-[11px] font-mono font-medium px-2 py-0.5 rounded-lg bg-slate-100 text-slate-700 border border-slate-200">0 NEW</span>
                    @endif
                </div>
            </a>
        </div>

        <div class="bg-white rounded-lg border border-slate-200 p-4 shadow-2xs col-span-2 md:col-span-1">
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

    <!-- BOTTOM GRID: Recent Inquiries, Recent News Articles & Top Page Traffic -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Inquiries Feed -->
        <div class="bg-white rounded-lg border border-slate-200 p-5 shadow-2xs">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                <h3 class="text-base font-semibold text-slate-900">Recent Inquiries</h3>
                <a href="{{ route('admin.messages.index') }}" class="text-xs font-semibold text-slate-700 hover:text-slate-900">View All &rarr;</a>
            </div>
            <div class="space-y-3">
                @forelse($recentMessages as $msg)
                <div class="p-3 rounded-lg bg-slate-50 border border-slate-200 flex items-center justify-between gap-3 hover:border-slate-300 transition-all">
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            @if(!$msg->is_read)
                                <span class="badge badge-pending text-[10px]">Unread</span>
                            @else
                                <span class="badge text-[10px] bg-slate-100 text-slate-600 border-slate-200">Read</span>
                            @endif
                            <span class="text-[11px] text-slate-400 font-mono">{{ $msg->created_at ? $msg->created_at->diffForHumans() : '' }}</span>
                        </div>
                        <p class="text-xs font-semibold text-slate-900 truncate">{{ $msg->name }}</p>
                        <p class="text-[11px] text-slate-500 truncate">{{ $msg->subject ?: Str::limit($msg->message, 40) }}</p>
                    </div>
                    <a href="{{ route('admin.messages.show', $msg) }}" class="btn btn-secondary py-1 px-2.5 text-xs">View</a>
                </div>
                @empty
                <p class="text-xs text-slate-400 text-center py-6">No inquiries received yet.</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Articles Feed -->
        <div class="bg-white rounded-lg border border-slate-200 p-5 shadow-2xs">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                <h3 class="text-base font-semibold text-slate-900">Recent News</h3>
                <a href="{{ route('admin.news.index') }}" class="text-xs font-semibold text-slate-700 hover:text-slate-900">View All &rarr;</a>
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
                <h3 class="text-base font-semibold text-slate-900">Top Pages (7D)</h3>
                <a href="{{ route('admin.analytics.index') }}" class="text-xs font-semibold text-slate-700 hover:text-slate-900">Analytics &rarr;</a>
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
                    No analytics recorded.
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- INTEGRATED GLOBAL SITE SETTINGS SECTION -->
    <div class="bg-white rounded-lg border border-slate-200 p-5 shadow-2xs" x-data="{ settingsOpen: false }">
        <div class="flex items-center justify-between cursor-pointer" @click="settingsOpen = !settingsOpen">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <h3 class="text-base font-semibold text-slate-900">Global Site Settings</h3>
                <span class="text-xs text-slate-400 font-mono">(General Config & Contact Details)</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs text-slate-500 font-medium hidden sm:inline" x-text="settingsOpen ? 'Collapse' : 'Expand Settings'"></span>
                <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="{'rotate-180': settingsOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
        </div>

        <div x-show="settingsOpen" x-collapse class="mt-4 pt-4 border-t border-slate-100">
            <form action="{{ route('admin.dashboard.settings') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- General Settings -->
                    <div class="space-y-4 bg-slate-50/70 p-4 rounded-xl border border-slate-200">
                        <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider border-b border-slate-200 pb-2">General Configuration</h4>
                        @forelse (($siteSettings['general'] ?? collect()) as $setting)
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1">{{ str_replace('_', ' ', $setting->key) }}</label>
                            <input type="text" name="settings[{{ $loop->index }}][value]" value="{{ is_array($setting->value) ? json_encode($setting->value) : $setting->value }}" class="text-xs w-full border border-slate-200 rounded-lg p-2.5 bg-white">
                            <input type="hidden" name="settings[{{ $loop->index }}][key]" value="{{ $setting->key }}">
                            <input type="hidden" name="settings[{{ $loop->index }}][locale]" value="{{ $setting->locale }}">
                            <input type="hidden" name="settings[{{ $loop->index }}][group]" value="general">
                        </div>
                        @empty
                        <p class="text-xs text-slate-400 py-2">No general settings configured.</p>
                        @endforelse
                    </div>

                    <!-- Contact Settings -->
                    <div class="space-y-4 bg-slate-50/70 p-4 rounded-xl border border-slate-200">
                        <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider border-b border-slate-200 pb-2">Contact & Support Details</h4>
                        @forelse (($siteSettings['contact'] ?? collect()) as $setting)
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="text-xs font-semibold text-slate-600 uppercase tracking-wider">{{ str_replace('_', ' ', $setting->key) }}</label>
                                <span class="text-[10px] font-mono uppercase bg-slate-200 text-slate-600 px-1.5 py-0.5 rounded">{{ $setting->locale ?? 'global' }}</span>
                            </div>
                            <input type="text" name="settings[{{ $loop->index + 100 }}][value]" value="{{ is_array($setting->value) ? json_encode($setting->value) : $setting->value }}" class="text-xs w-full border border-slate-200 rounded-lg p-2.5 bg-white">
                            <input type="hidden" name="settings[{{ $loop->index + 100 }}][key]" value="{{ $setting->key }}">
                            <input type="hidden" name="settings[{{ $loop->index + 100 }}][locale]" value="{{ $setting->locale }}">
                            <input type="hidden" name="settings[{{ $loop->index + 100 }}][group]" value="contact">
                        </div>
                        @empty
                        <p class="text-xs text-slate-400 py-2">No contact settings configured.</p>
                        @endforelse
                    </div>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <p class="text-xs text-slate-500">Changes update global site settings across all public landing pages.</p>
                    <button type="submit" class="btn btn-primary py-2 px-5 text-xs font-bold shadow-xs">
                        Save Site Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection