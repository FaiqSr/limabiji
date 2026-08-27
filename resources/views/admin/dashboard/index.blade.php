@extends('admin.layouts.app')
@section('title', 'Dashboard Console')
@section('page_title', 'Operations & Content Console')

@section('content')
<div class="space-y-6">

    @if (session('success'))
        <div class="p-3.5 rounded-lg bg-emerald-50 text-emerald-900 border border-emerald-200 text-xs font-semibold flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- FOCAL POINT: Quick Action & Operations Bar -->
    <div class="card-modern">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-start gap-3.5">
                <div class="w-10 h-10 rounded-lg bg-slate-900 text-white flex items-center justify-center shrink-0 shadow-2xs">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm sm:text-base font-bold text-slate-900 tracking-tight">Lima Biji Admin Operations Console</h2>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Manage specialty coffee storefront orders, stories, regional origins, and global distribution network.
                    </p>
                </div>
            </div>

            <!-- Quick Action Command Group -->
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary text-xs py-2 px-3.5 shadow-2xs">
                    + New Product
                </a>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary text-xs py-2 px-3">
                    View Orders
                </a>
                <a href="{{ route('admin.news.create') }}" class="btn btn-secondary text-xs py-2 px-3">
                    + New Article
                </a>
                <a href="{{ route('admin.origins.create') }}" class="btn btn-secondary text-xs py-2 px-3">
                    + New Origin
                </a>
            </div>
        </div>
    </div>

    <!-- STORE & COMMERCIAL KPI ROW -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="card-modern p-4">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block font-mono">Store Revenue</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="font-mono text-xl sm:text-2xl font-bold text-emerald-700">Rp {{ number_format($stats['store_revenue'], 0, ',', '.') }}</span>
                <span class="text-[10px] font-mono font-bold px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase">PAID</span>
            </div>
        </div>

        <div class="card-modern p-4">
            <a href="{{ route('admin.orders.index', ['shipping_status' => 'unfulfilled']) }}" class="block group">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block font-mono group-hover:text-amber-700 transition-colors">To Ship / Pack</span>
                <div class="flex items-baseline justify-between mt-2">
                    <span class="font-mono text-2xl font-bold text-amber-700">{{ number_format($stats['orders_unfulfilled_count']) }}</span>
                    @if ($stats['orders_unfulfilled_count'] > 0)
                        <span class="text-[10px] font-mono font-bold px-2 py-0.5 rounded bg-amber-50 text-amber-800 border border-amber-200 animate-pulse">ACTION NEEDED</span>
                    @else
                        <span class="text-[10px] font-mono font-bold px-2 py-0.5 rounded bg-slate-100 text-slate-700 border border-slate-200">CLEAR</span>
                    @endif
                </div>
            </a>
        </div>

        <div class="card-modern p-4">
            <a href="{{ route('admin.orders.index') }}" class="block group">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block font-mono group-hover:text-indigo-600 transition-colors">Total Orders</span>
                <div class="flex items-baseline justify-between mt-2">
                    <span class="font-mono text-2xl font-bold text-slate-900 group-hover:text-indigo-600 transition-colors">{{ number_format($stats['orders_count']) }}</span>
                    <span class="text-[10px] font-mono font-bold px-2 py-0.5 rounded bg-slate-100 text-slate-700 border border-slate-200">{{ number_format($stats['orders_paid_count']) }} PAID</span>
                </div>
            </a>
        </div>

        <div class="card-modern p-4">
            <a href="{{ route('admin.products.index') }}" class="block group">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block font-mono group-hover:text-indigo-600 transition-colors">Products Catalog</span>
                <div class="flex items-baseline justify-between mt-2">
                    <span class="font-mono text-2xl font-bold text-slate-900 group-hover:text-indigo-600 transition-colors">{{ number_format($stats['products_count']) }}</span>
                    @if ($stats['products_low_stock_count'] > 0)
                        <span class="text-[10px] font-mono font-bold px-2 py-0.5 rounded bg-amber-50 text-amber-800 border border-amber-200">{{ $stats['products_low_stock_count'] }} LOW STOCK</span>
                    @else
                        <span class="text-[10px] font-mono font-bold px-2 py-0.5 rounded bg-slate-100 text-slate-700 border border-slate-200">ACTIVE</span>
                    @endif
                </div>
            </a>
        </div>
    </div>

    <!-- CONTENT & DISTRIBUTION KPI ROW -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
        <div class="card-modern p-4">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block font-mono">Export Destinations</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="font-mono text-2xl font-bold text-slate-900 tracking-tight">{{ number_format($stats['export_destinations_count']) }}</span>
                <span class="text-[10px] font-mono font-bold px-1.5 py-0.5 rounded bg-slate-100 text-slate-700 border border-slate-200 uppercase">GLOBAL</span>
            </div>
        </div>

        <div class="card-modern p-4">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block font-mono">Published Articles</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="font-mono text-2xl font-bold text-slate-900 tracking-tight">{{ number_format($stats['articles_published']) }}</span>
                <span class="text-[10px] font-mono font-bold px-1.5 py-0.5 rounded bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase">{{ number_format($stats['articles_draft']) }} DRAFTS</span>
            </div>
        </div>

        <div class="card-modern p-4">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block font-mono">Coffee Origins</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="font-mono text-2xl font-bold text-slate-900 tracking-tight">{{ number_format($stats['origins_count']) }}</span>
                <span class="text-[10px] font-mono font-bold px-1.5 py-0.5 rounded bg-slate-100 text-slate-700 border border-slate-200 uppercase">REGIONS</span>
            </div>
        </div>

        <div class="card-modern p-4">
            <a href="{{ route('admin.messages.index') }}" class="block group">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block font-mono group-hover:text-indigo-600 transition-colors">Inquiries</span>
                <div class="flex items-baseline justify-between mt-2">
                    <span class="font-mono text-2xl font-bold text-slate-900 tracking-tight group-hover:text-indigo-600 transition-colors">{{ number_format($stats['messages_count']) }}</span>
                    @if ($stats['messages_unread'] > 0)
                        <span class="text-[10px] font-mono font-bold px-1.5 py-0.5 rounded bg-indigo-50 text-indigo-700 border border-indigo-200 uppercase">{{ number_format($stats['messages_unread']) }} NEW</span>
                    @else
                        <span class="text-[10px] font-mono font-bold px-1.5 py-0.5 rounded bg-slate-100 text-slate-700 border border-slate-200 uppercase">0 NEW</span>
                    @endif
                </div>
            </a>
        </div>

        <div class="card-modern p-4 col-span-2 md:col-span-1">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block font-mono">Page Views (7D)</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="font-mono text-2xl font-bold text-slate-900 tracking-tight">{{ number_format($stats['page_views_7d']) }}</span>
                <span class="text-[10px] font-mono font-bold px-1.5 py-0.5 rounded bg-slate-100 text-slate-700 border border-slate-200 uppercase">{{ number_format($stats['unique_visitors_7d']) }} UNIQUE</span>
            </div>
        </div>
    </div>

    <!-- STORE ORDERS OVERVIEW -->
    <div class="bg-white rounded-lg border border-slate-200 shadow-2xs overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-900">Recent Store Orders & Fulfillment</h3>
                <p class="text-xs text-slate-500 mt-0.5">Latest online purchases, payment confirmations, and courier dispatches.</p>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="text-xs font-semibold text-slate-700 hover:text-slate-900 flex items-center gap-1">
                Manage All Orders &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Destination</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Shipping</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentOrders as $order)
                        <tr>
                            <td class="font-mono font-bold text-xs">
                                <a href="{{ route('admin.orders.show', $order) }}" class="text-slate-900 hover:text-emerald-700 transition-colors">
                                    #{{ $order->order_number }}
                                </a>
                                <span class="block text-[11px] font-normal text-slate-400">{{ $order->created_at->diffForHumans() }}</span>
                            </td>
                            <td>
                                <div class="text-xs font-medium text-slate-900">{{ $order->customer_name }}</div>
                                <div class="text-[11px] text-slate-500 font-mono">{{ $order->customer_phone }}</div>
                            </td>
                            <td class="text-xs text-slate-700 font-medium">
                                {{ $order->city }}
                                <span class="block text-[11px] text-slate-400 font-mono">{{ $order->courier }}</span>
                            </td>
                            <td class="font-mono font-bold text-xs text-slate-900">
                                {{ $order->getFormattedTotal() }}
                            </td>
                            <td>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold uppercase font-mono {{ $order->getPaymentStatusBadgeClass() }}">
                                    {{ $order->getPaymentStatusLabel() }}
                                </span>
                            </td>
                            <td>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold uppercase font-mono {{ $order->getShippingStatusBadgeClass() }}">
                                    {{ $order->getShippingStatusLabel() }}
                                </span>
                            </td>
                            <td class="text-right">
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-secondary text-xs py-1 px-2.5">
                                    Manage
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-slate-400 text-xs">
                                No store orders recorded yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- SIGNATURE ELEMENT: Agritech Origin & Cupping Score Matrix -->
    <div class="bg-white rounded-lg border border-slate-200 shadow-2xs overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-900">Agritech Coffee Origins Matrix</h3>
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
                        <td class="font-semibold text-slate-900 text-xs">
                            <a href="{{ route('admin.origins.edit', $origin) }}" class="hover:text-emerald-700 transition-colors">
                                {{ $origin->name }}
                            </a>
                        </td>
                        <td class="text-xs text-slate-600 font-medium">{{ $origin->province }}</td>
                        <td>
                            <span class="font-mono text-xs font-semibold px-2 py-0.5 rounded bg-emerald-50 text-emerald-800 border border-emerald-200 inline-flex items-center gap-1">
                                <svg class="w-3 h-3 text-emerald-600 fill-current" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <span>{{ $origin->score ?: '85+' }}</span>
                            </span>
                        </td>
                        <td class="font-mono text-xs text-slate-600">{{ $origin->altitude }}</td>
                        <td class="text-xs text-slate-700 font-medium">{{ $origin->process }}</td>
                        <td class="font-mono text-xs text-slate-500">{{ $origin->harvest }}</td>
                        <td>
                            @if ($origin->is_active)
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold font-mono uppercase px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold font-mono uppercase px-2 py-0.5 rounded bg-slate-100 text-slate-500 border border-slate-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                    Inactive
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
        <div class="card-modern p-4">
            <div class="flex items-center justify-between mb-3 pb-2 border-b border-slate-100">
                <h3 class="text-sm font-bold text-slate-900">Recent Inquiries</h3>
                <a href="{{ route('admin.messages.index') }}" class="text-xs font-semibold text-slate-700 hover:text-slate-900">View All &rarr;</a>
            </div>
            <div class="space-y-2.5">
                @forelse($recentMessages as $msg)
                <div class="p-3 rounded-lg bg-slate-50 border border-slate-200/80 flex items-center justify-between gap-3 hover:border-slate-300 transition-all">
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            @if(!$msg->is_read)
                                <span class="badge badge-pending text-[9px]">Unread</span>
                            @else
                                <span class="badge text-[9px] bg-slate-100 text-slate-600 border-slate-200">Read</span>
                            @endif
                            <span class="text-[10px] text-slate-400 font-mono">{{ $msg->created_at ? $msg->created_at->diffForHumans() : '' }}</span>
                        </div>
                        <p class="text-xs font-semibold text-slate-900 truncate">{{ $msg->name }}</p>
                        <p class="text-[11px] text-slate-500 truncate">{{ $msg->subject ?: Str::limit($msg->message, 40) }}</p>
                    </div>
                    <a href="{{ route('admin.messages.show', $msg) }}" class="btn btn-secondary py-1 px-2.5 text-xs shrink-0">View</a>
                </div>
                @empty
                <p class="text-xs text-slate-400 text-center py-6">No inquiries received yet.</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Articles Feed -->
        <div class="card-modern p-4">
            <div class="flex items-center justify-between mb-3 pb-2 border-b border-slate-100">
                <h3 class="text-sm font-bold text-slate-900">Recent News</h3>
                <a href="{{ route('admin.news.index') }}" class="text-xs font-semibold text-slate-700 hover:text-slate-900">View All &rarr;</a>
            </div>
            <div class="space-y-2.5">
                @forelse($recentArticles as $recent)
                <div class="p-3 rounded-lg bg-slate-50 border border-slate-200/80 flex items-center justify-between gap-3 hover:border-slate-300 transition-all">
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="badge badge-{{ $recent->status }} text-[9px]">{{ ucfirst($recent->status) }}</span>
                            <span class="text-[10px] text-slate-400 font-mono">{{ $recent->category }}</span>
                        </div>
                        <p class="text-xs font-semibold text-slate-900 truncate">{{ $recent->title }}</p>
                        @if($recent->title_id)
                            <p class="text-[11px] text-slate-500 truncate font-sans">
                                <span class="text-[9px] font-bold font-mono px-1 py-0.2 rounded bg-slate-200 text-slate-700">ID</span> {{ $recent->title_id }}
                            </p>
                        @endif
                    </div>
                    <a href="{{ route('admin.news.edit', $recent) }}" class="btn btn-secondary py-1 px-2.5 text-xs shrink-0">Edit</a>
                </div>
                @empty
                <p class="text-xs text-slate-400 text-center py-6">No articles published yet.</p>
                @endforelse
            </div>
        </div>

        <!-- Top Pages Analytics Matrix -->
        <div class="card-modern p-4">
            <div class="flex items-center justify-between mb-3 pb-2 border-b border-slate-100">
                <h3 class="text-sm font-bold text-slate-900">Top Pages (7D)</h3>
                <a href="{{ route('admin.analytics.index') }}" class="text-xs font-semibold text-slate-700 hover:text-slate-900">Analytics &rarr;</a>
            </div>
            <div class="space-y-2">
                @forelse($stats['top_pages'] as $pageItem)
                <div class="p-2.5 rounded-lg bg-slate-50 border border-slate-200/80 flex items-center justify-between font-mono text-xs">
                    <span class="text-slate-800 font-medium truncate">/{{ $pageItem['page'] ?? '' }}</span>
                    <span class="px-2 py-0.5 rounded bg-white text-slate-700 font-semibold border border-slate-200 shrink-0 text-[11px]">
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
    <div class="card-modern p-4" x-data="{ settingsOpen: false }">
        <div class="flex items-center justify-between cursor-pointer select-none" @click="settingsOpen = !settingsOpen">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Global Site Settings</h3>
                    <p class="text-[11px] text-slate-500 font-mono">Quick Brand & Contact Details Configuration</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-semibold text-slate-600" x-text="settingsOpen ? 'Collapse Settings' : 'Expand Settings'"></span>
                <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="settingsOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
        </div>

        <div x-show="settingsOpen" x-collapse class="mt-4 pt-4 border-t border-slate-100">
            <form action="{{ route('admin.dashboard.settings') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                @php $settingIndex = 0; @endphp
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($siteSettings as $groupName => $items)
                        @foreach ($items as $setting)
                            <div class="space-y-1">
                                <label class="text-xs font-semibold text-slate-700 capitalize">
                                    {{ str_replace('_', ' ', $setting->key) }}
                                    @if ($setting->locale)
                                        <span class="text-[10px] font-mono text-slate-400 font-normal">({{ strtoupper($setting->locale) }})</span>
                                    @endif
                                </label>
                                <input type="hidden" name="settings[{{ $settingIndex }}][key]" value="{{ $setting->key }}">
                                <input type="hidden" name="settings[{{ $settingIndex }}][group]" value="{{ $setting->group }}">
                                <input type="hidden" name="settings[{{ $settingIndex }}][locale]" value="{{ $setting->locale }}">
                                <input type="text" name="settings[{{ $settingIndex }}][value]" value="{{ old('settings.'.$settingIndex.'.value', $setting->value) }}" class="text-xs">
                            </div>
                            @php $settingIndex++; @endphp
                        @endforeach
                    @endforeach
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="btn btn-primary text-xs py-2 px-4 shadow-2xs cursor-pointer">
                        Save Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection