@extends('admin.layouts.app')
@section('title', 'Certificates & Quality Standards')
@section('page_title', 'Certificates Management')

@section('content')
<div class="space-y-6">
    <!-- Header Toolbar: Search & Add Certificate -->
    <div class="card-modern space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <!-- Search Form Input -->
            <form method="GET" action="{{ route('admin.certificates.index') }}" class="flex-1 relative">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <div class="relative">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Search certificates by name, issuer, or cert number..." 
                           class="w-full pl-9 pr-8 text-xs bg-slate-50 border border-slate-200 rounded-xl p-2.5 focus:bg-white focus:ring-2 focus:ring-indigo-500 transition-all">
                    
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>

                    @if (request('search'))
                        <a href="{{ route('admin.certificates.index', ['status' => request('status', 'all')]) }}" 
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
                    <a href="{{ route('admin.certificates.index', array_merge(request()->query(), ['status' => 'all'])) }}" 
                       class="px-3 py-1.5 rounded-lg transition-all {{ $status === 'all' ? 'bg-white text-slate-900 font-semibold shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
                        All ({{ $counts['all'] }})
                    </a>
                    <a href="{{ route('admin.certificates.index', array_merge(request()->query(), ['status' => 'active'])) }}" 
                       class="px-3 py-1.5 rounded-lg transition-all {{ $status === 'active' ? 'bg-white text-emerald-700 font-semibold shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
                        Active ({{ $counts['active'] }})
                    </a>
                    <a href="{{ route('admin.certificates.index', array_merge(request()->query(), ['status' => 'inactive'])) }}" 
                       class="px-3 py-1.5 rounded-lg transition-all {{ $status === 'inactive' ? 'bg-white text-slate-700 font-semibold shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
                        Inactive ({{ $counts['inactive'] }})
                    </a>
                </div>

                <a href="{{ route('admin.certificates.create') }}" class="btn btn-primary text-xs py-2 px-4 shadow-2xs w-full sm:w-fit">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Add Certificate</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Certificate List Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/75 text-[11px] font-bold uppercase tracking-wider text-slate-500 font-mono">
                        <th class="py-3.5 px-4 text-center w-14">Order</th>
                        <th class="py-3.5 px-4 w-20">Logo</th>
                        <th class="py-3.5 px-5">Certificate & Issuer</th>
                        <th class="py-3.5 px-4">Certificate Number</th>
                        <th class="py-3.5 px-4">Validity</th>
                        <th class="py-3.5 px-4 text-center w-24">Status</th>
                        <th class="py-3.5 px-5 text-right w-36">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                    @forelse ($certificates as $cert)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <!-- Order -->
                            <td class="py-4 px-4 text-center font-mono font-bold text-slate-500">
                                <span class="w-7 h-7 rounded-lg bg-slate-100 inline-flex items-center justify-center text-xs">
                                    {{ $cert->order }}
                                </span>
                            </td>

                            <!-- Logo -->
                            <td class="py-4 px-4">
                                <div class="w-12 h-12 rounded-xl bg-slate-100 border border-slate-200 p-1 flex items-center justify-center overflow-hidden">
                                    <img src="{{ $cert->logo }}" alt="{{ $cert->name }}" class="w-full h-full object-cover rounded-lg">
                                </div>
                            </td>

                            <!-- Certificate & Issuer -->
                            <td class="py-4 px-5">
                                <div class="font-bold text-slate-900 text-sm leading-snug flex items-center gap-2">
                                    <span>{{ $cert->name }}</span>
                                </div>
                                @if($cert->issuer)
                                    <div class="text-[11px] text-slate-500 mt-0.5">
                                        {{ $cert->issuer }}
                                    </div>
                                @endif
                            </td>

                            <!-- Certificate Number -->
                            <td class="py-4 px-4">
                                <span class="font-mono text-xs bg-slate-100 text-slate-800 px-2.5 py-1 rounded-md font-semibold border border-slate-200 inline-block">
                                    {{ $cert->certificate_number }}
                                </span>
                            </td>

                            <!-- Validity -->
                            <td class="py-4 px-4 text-slate-600">
                                @if($cert->issued_date || $cert->expiry_date)
                                    <div class="font-mono text-[11px]">{{ $cert->issued_date ? $cert->issued_date->format('M Y') : '—' }} - {{ $cert->expiry_date ? $cert->expiry_date->format('M Y') : 'Lifetime' }}</div>
                                @else
                                    <span class="text-slate-400 italic">Not set</span>
                                @endif
                            </td>

                            <!-- Status Toggle -->
                            <td class="py-4 px-4 text-center">
                                <form action="{{ route('admin.certificates.toggle-active', $cert) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="cursor-pointer inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold border transition-all {{ $cert->is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100' : 'bg-slate-100 text-slate-500 border-slate-200 hover:bg-slate-200' }}"
                                            title="Click to toggle status">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $cert->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                        {{ $cert->is_active ? 'Active' : 'Inactive' }}
                                    </button>
                                </form>
                            </td>

                            <!-- Actions -->
                            <td class="py-4 px-5 text-right space-x-1.5">
                                <a href="{{ route('admin.certificates.edit', $cert) }}" 
                                   class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium text-slate-700 hover:text-indigo-600 bg-slate-100 hover:bg-indigo-50 rounded-lg transition-colors"
                                   title="Edit Certificate">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    <span>Edit</span>
                                </a>

                                <form action="{{ route('admin.certificates.destroy', $cert) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Are you sure you want to delete this certificate?');" 
                                      class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium text-rose-600 hover:text-rose-700 bg-rose-50 hover:bg-rose-100 rounded-lg transition-colors cursor-pointer"
                                            title="Delete Certificate">
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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                    <span class="font-medium text-xs">No certificates found</span>
                                    @if(request('search'))
                                        <a href="{{ route('admin.certificates.index') }}" class="text-xs text-indigo-600 hover:underline">Clear search</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($certificates->hasPages())
            <div class="px-5 py-3 border-t border-slate-100 bg-slate-50/50">
                {{ $certificates->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
