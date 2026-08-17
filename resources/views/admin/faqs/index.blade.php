@extends('admin.layouts.app')
@section('title', 'FAQs Management')
@section('page_title', 'Frequently Asked Questions')

@section('content')
<div class="space-y-6">
    <!-- Header Toolbar: Search & Add FAQ -->
    <div class="card-modern space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <!-- Search Form Input -->
            <form method="GET" action="{{ route('admin.faqs.index') }}" class="flex-1 relative">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <div class="relative">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Search FAQs by question or answer (EN / ID)..." 
                           class="w-full pl-9 pr-8 text-xs bg-slate-50 border border-slate-200 rounded-xl p-2.5 focus:bg-white focus:ring-2 focus:ring-indigo-500 transition-all">
                    
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>

                    @if (request('search'))
                        <a href="{{ route('admin.faqs.index', ['status' => request('status', 'all')]) }}" 
                           class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600"
                           title="Clear search">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </a>
                    @endif
                </div>
            </form>

            <!-- Status Tabs & Create Button -->
            <div class="flex flex-wrap items-center gap-3">
                <div class="flex items-center gap-1 p-1 bg-slate-100 rounded-xl text-xs font-medium">
                    <a href="{{ route('admin.faqs.index', array_merge(request()->query(), ['status' => 'all'])) }}" 
                       class="px-3 py-1.5 rounded-lg transition-all {{ $status === 'all' ? 'bg-white text-slate-900 font-semibold shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
                        All ({{ $counts['all'] }})
                    </a>
                    <a href="{{ route('admin.faqs.index', array_merge(request()->query(), ['status' => 'active'])) }}" 
                       class="px-3 py-1.5 rounded-lg transition-all {{ $status === 'active' ? 'bg-white text-emerald-700 font-semibold shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
                        Active ({{ $counts['active'] }})
                    </a>
                    <a href="{{ route('admin.faqs.index', array_merge(request()->query(), ['status' => 'inactive'])) }}" 
                       class="px-3 py-1.5 rounded-lg transition-all {{ $status === 'inactive' ? 'bg-white text-slate-700 font-semibold shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
                        Inactive ({{ $counts['inactive'] }})
                    </a>
                </div>

                <a href="{{ route('admin.faqs.create') }}" class="btn btn-primary text-xs py-2 px-4 shadow-2xs w-full sm:w-fit">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Add FAQ</span>
                </a>
            </div>
        </div>
    </div>

    <!-- FAQs Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/75 text-[11px] font-bold uppercase tracking-wider text-slate-500 font-mono">
                        <th class="py-3.5 px-4 text-center w-14">Order</th>
                        <th class="py-3.5 px-4">Question (EN / ID)</th>
                        <th class="py-3.5 px-4 max-w-xs">Answer Preview</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                    @forelse ($faqs as $faq)
                        <tr class="hover:bg-slate-50/75 transition-colors group">
                            <!-- Order Column -->
                            <td class="py-4 px-4 text-center font-mono font-bold text-slate-500">
                                <span class="w-7 h-7 rounded-lg bg-slate-100 inline-flex items-center justify-center text-xs">
                                    {{ $faq->order }}
                                </span>
                            </td>

                            <!-- Question (EN / ID) -->
                            <td class="py-4 px-4">
                                <div class="font-bold text-slate-900 leading-snug">
                                    {{ $faq->question }}
                                </div>
                                @if ($faq->question_id)
                                    <div class="text-[11px] text-slate-500 mt-1 flex items-start gap-1.5">
                                        <span class="px-1.5 py-0.2 rounded bg-slate-100 font-mono text-[9px] font-bold text-slate-600 mt-0.5">ID</span>
                                        <span class="leading-tight">{{ $faq->question_id }}</span>
                                    </div>
                                @endif
                            </td>

                            <!-- Answer Preview -->
                            <td class="py-4 px-4 max-w-xs">
                                <p class="text-slate-600 line-clamp-2 leading-relaxed text-xs">
                                    {{ Str::limit(strip_tags($faq->answer), 120) }}
                                </p>
                            </td>

                            <!-- Status Column with quick toggle -->
                            <td class="py-4 px-4 text-center whitespace-nowrap">
                                <form action="{{ route('admin.faqs.toggle-active', $faq) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" 
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold transition-all cursor-pointer {{ $faq->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100' : 'bg-slate-100 text-slate-500 border border-slate-200 hover:bg-slate-200' }}"
                                            title="Click to toggle status">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $faq->is_active ? 'bg-emerald-600' : 'bg-slate-400' }}"></span>
                                        <span>{{ $faq->is_active ? 'Active' : 'Inactive' }}</span>
                                    </button>
                                </form>
                            </td>

                            <!-- Actions -->
                            <td class="py-4 px-5 text-right whitespace-nowrap space-x-1.5">
                                <a href="{{ route('admin.faqs.edit', $faq) }}" 
                                   class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium text-slate-700 hover:text-indigo-600 bg-slate-100 hover:bg-indigo-50 rounded-lg transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    <span>Edit</span>
                                </a>

                                <form action="{{ route('admin.faqs.destroy', $faq) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this FAQ item?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium text-rose-600 hover:text-rose-700 bg-rose-50 hover:bg-rose-100 rounded-lg transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        <span>Delete</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 px-5 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="font-medium text-xs">No FAQ items found</span>
                                    @if(request('search') || request('status') !== 'all')
                                        <a href="{{ route('admin.faqs.index') }}" class="text-xs text-indigo-600 hover:underline">Clear filters</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($faqs->hasPages())
            <div class="px-5 py-3 border-t border-slate-100 bg-slate-50/50">
                {{ $faqs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
