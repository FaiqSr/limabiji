@extends('admin.layouts.app')
@section('title', 'Origins')
@section('page_title', 'Coffee Origins Management')

@section('content')
<div class="card-modern">
    <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
        <div>
            <h3 class="text-base font-semibold text-slate-900">Indonesian Coffee Origins</h3>
            <p class="text-xs text-slate-500">Manage specialty coffee regions, cupping scores, altitudes, and processing methods.</p>
        </div>
        <a href="{{ route('admin.origins.create') }}" class="btn btn-primary text-xs py-2 px-4 shadow-xs">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add New Origin
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="table-modern">
            <thead>
                <tr>
                    <th class="w-14">Image</th>
                    <th>Region Name</th>
                    <th>Province</th>
                    <th>Gallery</th>
                    <th>Cupping Score</th>
                    <th>Altitude</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($origins as $origin)
                <tr>
                    <td>
                        @if ($origin->image)
                            <img src="{{ $origin->image }}" alt="{{ $origin->name }}" class="w-10 h-10 object-cover rounded-lg border border-slate-200 shadow-2xs">
                        @else
                            <div class="w-10 h-10 rounded-lg bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400 text-xs">
                                No img
                            </div>
                        @endif
                    </td>
                    <td class="font-semibold text-slate-900">{{ $origin->name }}</td>
                    <td class="text-slate-600">{{ $origin->province }}</td>
                    <td>
                        @php $galleryCount = count($origin->gallery ?: []); @endphp
                        <span class="inline-flex items-center gap-1 text-xs font-medium px-2 py-0.5 rounded-lg {{ $galleryCount > 0 ? 'bg-slate-100 text-slate-700 border border-slate-200' : 'bg-slate-50 text-slate-400 border border-slate-200' }}">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ $galleryCount }} {{ Str::plural('photo', $galleryCount) }}
                        </span>
                    </td>
                    <td>
                        <span class="inline-flex items-center gap-1 font-mono text-xs font-semibold px-2 py-0.5 rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-200">
                            {{ $origin->score }}
                        </span>
                    </td>
                    <td class="font-mono text-xs text-slate-600">{{ $origin->altitude }}</td>
                    <td>
                        @if ($origin->is_active)
                            <span class="badge badge-published">Active</span>
                        @else
                            <span class="badge badge-draft">Inactive</span>
                        @endif
                    </td>
                    <td class="text-right">
                        <a href="{{ route('admin.origins.edit', $origin) }}" class="btn btn-secondary py-1.5 px-3 text-xs">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-10 text-slate-400">
                        No coffee origins found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection