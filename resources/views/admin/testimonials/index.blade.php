@extends('admin.layouts.app')
@section('title', 'Testimonials')
@section('page_title', 'Client Testimonials Management')

@section('content')
<div class="card-modern">
    <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
        <div>
            <h3 class="text-base font-semibold text-slate-900">Client Reviews & Testimonials</h3>
            <p class="text-xs text-slate-500">Manage client feedback, ratings, and featured testimonials for the landing page.</p>
        </div>
        <a href="{{ route('admin.testimonials.create') }}" class="btn btn-primary text-xs py-2 px-4 shadow-xs">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Testimonial
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>Client Name</th>
                    <th>Company / Role</th>
                    <th>Rating</th>
                    <th>Featured Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($testimonials as $testimonial)
                <tr>
                    <td class="font-semibold text-slate-900">{{ $testimonial->name }}</td>
                    <td class="text-slate-600">{{ $testimonial->company ?? '—' }}</td>
                    <td>
                        <div class="flex items-center gap-0.5 text-amber-400">
                            @for ($i = 0; $i < 5; $i++)
                                @if ($i < $testimonial->rating)
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 text-slate-200 fill-current" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endif
                            @endfor
                            <span class="ml-1 text-xs font-mono font-semibold text-slate-600">({{ $testimonial->rating }}/5)</span>
                        </div>
                    </td>
                    <td>
                        @if ($testimonial->is_featured)
                            <span class="badge bg-amber-50 text-amber-700 border border-amber-200">Featured</span>
                        @else
                            <span class="text-xs text-slate-400 font-mono">Standard</span>
                        @endif
                    </td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="btn btn-secondary py-1.5 px-3 text-xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit
                            </a>
                            <form action="{{ route('admin.testimonials.destroy', $testimonial) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this testimonial?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-secondary text-rose-600 hover:text-rose-700 hover:bg-rose-50 border-rose-200 py-1.5 px-2.5 text-xs font-semibold">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-10 text-slate-400">
                        No testimonials found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection