@extends('admin.layouts.app')
@section('title', 'Export Map Locations')
@section('page_title', 'Export Map Locations')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 rounded-lg border border-slate-200 shadow-2xs">
        <div>
            <h2 class="text-base font-semibold text-slate-900">Global Export Map Destinations</h2>
            <p class="text-xs text-slate-500 mt-0.5">Manage countries and map coordinates displayed on the live homepage export map.</p>
        </div>
        <a href="{{ route('admin.export-destinations.create') }}" class="btn btn-primary text-xs py-2 px-4 shrink-0">
            + Add Destination
        </a>
    </div>

    @if (session('success'))
        <div class="p-4 rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-semibold">
            ✓ {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg border border-slate-200 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th class="w-12">Flag</th>
                        <th>Country Name (EN / ID)</th>
                        <th>Country Code</th>
                        <th>Coordinates (Lon, Lat)</th>
                        <th>Description (EN)</th>
                        <th>Order</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($destinations as $dest)
                    <tr>
                        <td>
                            <img src="https://flagcdn.com/w40/{{ $dest->country_code }}.png" alt="{{ $dest->name }}" class="w-7 h-5 object-cover rounded-lg border border-slate-200 shadow-2xs">
                        </td>
                        <td>
                            <div class="font-semibold text-slate-900 text-sm">{{ $dest->name }}</div>
                            @if($dest->name_id)
                                <div class="text-xs text-slate-500">🇮🇩 {{ $dest->name_id }}</div>
                            @endif
                        </td>
                        <td class="font-mono text-xs font-semibold text-slate-900 uppercase">{{ $dest->country_code }}</td>
                        <td class="font-mono text-xs text-slate-600">
                            [{{ number_format($dest->longitude, 4) }}, {{ number_format($dest->latitude, 4) }}]
                        </td>
                        <td class="text-xs text-slate-600 max-w-xs truncate" title="{{ $dest->description }}">
                            {{ $dest->description ?: '-' }}
                        </td>
                        <td class="font-mono text-xs text-slate-700 font-semibold">{{ $dest->order }}</td>
                        <td>
                            @if($dest->is_active)
                                <span class="inline-flex items-center gap-1 text-[11px] font-medium px-2 py-0.5 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    ● Active
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-[11px] font-medium px-2 py-0.5 rounded-lg bg-slate-100 text-slate-500 border border-slate-200">
                                    ○ Hidden
                                </span>
                            @endif
                        </td>
                        <td class="text-right">
                            <div class="inline-flex items-center gap-2">
                                <a href="{{ route('admin.export-destinations.edit', $dest) }}" class="btn btn-secondary py-1 px-2.5 text-xs">Edit</a>
                                <form action="{{ route('admin.export-destinations.destroy', $dest) }}" method="POST" onsubmit="return confirm('Delete this export destination?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger py-1 px-2.5 text-xs">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-10 text-slate-400 text-xs">
                            No export map destinations added yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
