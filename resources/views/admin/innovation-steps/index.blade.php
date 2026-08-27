@extends('admin.layouts.app')
@section('title', 'Process Steps')
@section('page_title', 'Enzymatic Innovation Steps')

@section('content')
<div class="space-y-6">
    <!-- Header Toolbar: Search, Status Tabs & Add Step -->
    <div class="card-modern space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <!-- Search Form Input -->
            <form method="GET" action="{{ route('admin.innovation-steps.index') }}" class="flex-1 relative">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <div class="relative">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Search process steps by title, description, or step number..." 
                           class="w-full pl-9 pr-8 text-xs bg-slate-50 border border-slate-200 rounded-xl p-2.5 focus:bg-white focus:ring-2 focus:ring-emerald-500 transition-all">
                    
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>

                    @if (request('search'))
                        <a href="{{ route('admin.innovation-steps.index', ['status' => request('status', 'all')]) }}" 
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
                    <a href="{{ route('admin.innovation-steps.index', array_merge(request()->query(), ['status' => 'all'])) }}" 
                       class="px-3 py-1.5 rounded-lg transition-all {{ (!request('status') || request('status') === 'all') ? 'bg-white text-slate-900 font-semibold shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
                        All ({{ $counts['all'] ?? $steps->total() }})
                    </a>
                    <a href="{{ route('admin.innovation-steps.index', array_merge(request()->query(), ['status' => 'active'])) }}" 
                       class="px-3 py-1.5 rounded-lg transition-all {{ request('status') === 'active' ? 'bg-white text-emerald-700 font-semibold shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
                        Active ({{ $counts['active'] ?? \App\Models\InnovationStep::where('is_active', true)->count() }})
                    </a>
                    <a href="{{ route('admin.innovation-steps.index', array_merge(request()->query(), ['status' => 'inactive'])) }}" 
                       class="px-3 py-1.5 rounded-lg transition-all {{ request('status') === 'inactive' ? 'bg-white text-slate-700 font-semibold shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
                        Inactive ({{ $counts['inactive'] ?? \App\Models\InnovationStep::where('is_active', false)->count() }})
                    </a>
                </div>

                <a href="{{ route('admin.innovation-steps.create') }}" class="btn btn-primary text-xs py-2 px-4 shadow-2xs w-full sm:w-fit">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Add New Step</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Steps Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/75 text-[11px] font-bold uppercase tracking-wider text-slate-500 font-mono">
                        <th class="py-3.5 px-4 text-center w-14">Step</th>
                        <th class="py-3.5 px-4 w-20">Image</th>
                        <th class="py-3.5 px-5">Title & Description (EN / ID)</th>
                        <th class="py-3.5 px-4 w-48">Key Tag Details</th>
                        <th class="py-3.5 px-4 text-center w-16">Order</th>
                        <th class="py-3.5 px-4 text-center w-24">Status</th>
                        <th class="py-3.5 px-5 text-right w-36">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                    @forelse($steps as $step)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <!-- Step Badge -->
                            <td class="py-4 px-4 text-center font-mono font-bold text-slate-900">
                                <span class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 inline-flex items-center justify-center text-xs font-bold">
                                    {{ $step->formatted_step_number }}
                                </span>
                            </td>

                            <!-- Image -->
                            <td class="py-4 px-4">
                                @if($step->image)
                                    <div class="w-14 h-12 rounded-xl bg-slate-100 border border-slate-200 p-0.5 overflow-hidden flex items-center justify-center">
                                        <img src="{{ $step->image }}" alt="{{ $step->title }}" class="w-full h-full object-cover rounded-lg">
                                    </div>
                                @else
                                    <div class="w-14 h-12 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400 font-mono text-[9px]">
                                        No Image
                                    </div>
                                @endif
                            </td>

                            <!-- Title & Description -->
                            <td class="py-4 px-5">
                                <div class="font-bold text-slate-900 text-sm leading-snug flex items-center gap-2">
                                    <span>{{ $step->title }}</span>
                                </div>
                                @if ($step->title_id)
                                    <div class="text-[11px] text-slate-500 mt-0.5 flex items-center gap-1.5">
                                        <span class="px-1.5 py-0.2 rounded bg-slate-100 font-mono text-[9px] font-bold text-slate-600">ID</span>
                                        <span>{{ $step->title_id }}</span>
                                    </div>
                                @endif
                                <p class="text-xs text-slate-500 line-clamp-2 mt-1 max-w-xl">
                                    {{ $step->description }}
                                </p>
                            </td>

                            <!-- Key Details -->
                            <td class="py-4 px-4">
                                @if($step->details)
                                    <div class="flex flex-wrap gap-1">
                                        @foreach(array_slice(array_map('trim', explode(',', $step->details)), 0, 3) as $tag)
                                            <span class="text-[10px] bg-slate-100 text-slate-600 px-2 py-0.5 rounded-md border border-slate-200 font-medium">
                                                {{ $tag }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-slate-400 text-[11px] italic">—</span>
                                @endif
                            </td>

                            <!-- Order -->
                            <td class="py-4 px-4 text-center font-mono font-bold text-slate-500">
                                <span class="w-7 h-7 rounded-lg bg-slate-100 inline-flex items-center justify-center text-xs">
                                    {{ $step->order }}
                                </span>
                            </td>

                            <!-- Status Toggle -->
                            <td class="py-4 px-4 text-center">
                                <form action="{{ route('admin.innovation-steps.toggle-active', $step) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="cursor-pointer inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold border transition-all {{ $step->is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100' : 'bg-slate-100 text-slate-500 border-slate-200 hover:bg-slate-200' }}"
                                            title="Click to toggle status">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $step->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                        {{ $step->is_active ? 'Active' : 'Inactive' }}
                                    </button>
                                </form>
                            </td>

                            <!-- Actions -->
                            <td class="py-4 px-5 text-right space-x-1.5">
                                <a href="{{ route('admin.innovation-steps.edit', $step) }}" 
                                   class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium text-slate-700 hover:text-emerald-600 bg-slate-100 hover:bg-emerald-50 rounded-lg transition-colors"
                                   title="Edit Step">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    <span>Edit</span>
                                </a>

                                <form action="{{ route('admin.innovation-steps.destroy', $step) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this process step?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium text-rose-600 hover:text-rose-700 bg-rose-50 hover:bg-rose-100 rounded-lg transition-colors cursor-pointer"
                                            title="Delete Step">
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
                            <td colspan="7" class="py-12 px-5 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                                    </svg>
                                    <span class="font-medium text-xs">No innovation steps found</span>
                                    @if(request('search'))
                                        <a href="{{ route('admin.innovation-steps.index') }}" class="text-xs text-emerald-600 hover:underline">Clear search</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($steps->hasPages())
            <div class="px-5 py-3 border-t border-slate-100 bg-slate-50/50">
                {{ $steps->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
