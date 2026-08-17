@extends('admin.layouts.app')
@section('title', 'Innovation Process Steps')
@section('page_title', 'Process Steps Management')

@section('content')
<div class="space-y-6">
    <!-- Header & Action Bar -->
    <div class="card-modern">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-base font-semibold text-slate-900">Enzymatic Innovation Steps</h3>
                <p class="text-xs text-slate-500">Manage the step-by-step enzymatic civet coffee innovation process displayed on the /innovation landing page.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.innovation-steps.create') }}" class="btn btn-primary py-2 px-4 text-xs font-bold shadow-xs flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Add New Step</span>
                </a>
            </div>
        </div>

        <!-- Filters & Search -->
        <div class="mt-4 pt-4 border-t border-slate-100 flex flex-col md:flex-row items-center justify-between gap-3">
            <form action="{{ route('admin.innovation-steps.index') }}" method="GET" class="w-full md:w-auto flex-1 max-w-md flex items-center gap-2">
                <div class="relative w-full">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Search steps by title, description, or step number..." 
                           class="w-full text-xs bg-slate-50 border border-slate-200 rounded-lg p-2.5 pl-9 focus:bg-white focus:ring-2 focus:ring-slate-900 focus:outline-hidden transition-all">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <button type="submit" class="btn btn-secondary py-2.5 px-3 text-xs">Search</button>
                @if(request('search'))
                    <a href="{{ route('admin.innovation-steps.index', ['status' => request('status')]) }}" class="text-xs text-slate-500 hover:text-slate-900 p-2">Clear</a>
                @endif
            </form>

            <div class="flex items-center gap-1.5 self-start md:self-auto text-xs">
                <span class="text-slate-400 text-[11px] font-mono uppercase mr-1">Status:</span>
                <a href="{{ route('admin.innovation-steps.index', ['search' => request('search')]) }}" 
                   class="px-2.5 py-1 rounded-lg font-medium transition-colors {{ !request('status') ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    All
                </a>
                <a href="{{ route('admin.innovation-steps.index', ['status' => 'active', 'search' => request('search')]) }}" 
                   class="px-2.5 py-1 rounded-lg font-medium transition-colors {{ request('status') === 'active' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Active
                </a>
                <a href="{{ route('admin.innovation-steps.index', ['status' => 'inactive', 'search' => request('search')]) }}" 
                   class="px-2.5 py-1 rounded-lg font-medium transition-colors {{ request('status') === 'inactive' ? 'bg-rose-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Inactive
                </a>
            </div>
        </div>
    </div>

    <!-- Steps Table -->
    <div class="card-modern p-0 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/75 border-b border-slate-200 text-slate-700 uppercase font-mono tracking-wider text-[10px]">
                    <tr>
                        <th class="px-5 py-3.5 w-16">Step</th>
                        <th class="px-5 py-3.5 w-24">Image</th>
                        <th class="px-5 py-3.5">Title & Descriptions</th>
                        <th class="px-5 py-3.5 w-48">Key Tag Details</th>
                        <th class="px-5 py-3.5 w-20 text-center">Order</th>
                        <th class="px-5 py-3.5 w-24 text-center">Status</th>
                        <th class="px-5 py-3.5 w-28 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($steps as $step)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-5 py-4 font-mono font-bold text-slate-900">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold">
                                {{ $step->formatted_step_number }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            @if($step->image)
                                <img src="{{ $step->image }}" alt="{{ $step->title }}" class="w-16 h-12 rounded-lg object-cover border border-slate-200 bg-slate-100 shadow-2xs">
                            @else
                                <div class="w-16 h-12 rounded-lg bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400 font-mono text-[9px]">
                                    No Image
                                </div>
                            @endif
                        </td>
                        <td class="px-5 py-4 space-y-1">
                            <div class="font-bold text-slate-900 text-sm flex items-center gap-2">
                                <span>{{ $step->title }}</span>
                                @if($step->title_id)
                                    <span class="text-[10px] font-mono bg-slate-100 text-slate-500 px-1.5 py-0.5 rounded">ID: {{ $step->title_id }}</span>
                                @endif
                            </div>
                            <p class="text-xs text-slate-500 line-clamp-2 max-w-xl">
                                {{ $step->description }}
                            </p>
                        </td>
                        <td class="px-5 py-4">
                            @if($step->details)
                                <div class="flex flex-wrap gap-1">
                                    @foreach(array_slice(array_map('trim', explode(',', $step->details)), 0, 3) as $tag)
                                        <span class="text-[10px] bg-slate-100 text-slate-600 px-2 py-0.5 rounded border border-slate-200 font-medium">
                                            {{ $tag }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-slate-400 text-[11px] italic">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-center font-mono font-semibold text-slate-700">
                            {{ $step->order }}
                        </td>
                        <td class="px-5 py-4 text-center">
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
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('admin.innovation-steps.edit', $step) }}" 
                                   class="p-1.5 text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-md transition-colors"
                                   title="Edit Step">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>

                                <form action="{{ route('admin.innovation-steps.destroy', $step) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this step?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="p-1.5 text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-md transition-colors cursor-pointer"
                                            title="Delete Step">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-12 text-center text-slate-400">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                                </svg>
                                <p class="text-sm font-medium">No innovation steps found.</p>
                                <p class="text-xs text-slate-400">Add a new step to appear on the /innovation landing page.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($steps->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $steps->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
