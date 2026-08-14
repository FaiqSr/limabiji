@extends('admin.layouts.app')
@section('title', 'Testimonials')
@section('page_title', 'Client Testimonials Management')

@section('content')
<div class="space-y-6">
    <!-- Header Toolbar -->
    <div class="card-modern flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <h3 class="text-base font-bold text-slate-900">Client Reviews & Testimonials</h3>
                <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-slate-100 text-slate-700 border border-slate-200">
                    {{ count($testimonials) }} {{ Str::plural('Review', count($testimonials)) }}
                </span>
            </div>
            <p class="text-xs text-slate-500 mt-0.5">Manage partner reviews, client feedback, and featured testimonials for the public landing pages.</p>
        </div>

        <a href="{{ route('admin.testimonials.create') }}" class="btn btn-primary text-xs py-2 px-4 shadow-2xs self-start sm:self-auto shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Add Testimonial</span>
        </a>
    </div>

    <!-- Testimonials Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        @forelse ($testimonials as $testimonial)
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-2xs hover:shadow-md hover:border-slate-300 transition-all duration-200 flex flex-col justify-between p-5 space-y-4 group">
                <div class="space-y-3.5">
                    <!-- Client Header & Featured Badge -->
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            @if ($testimonial->image)
                                <img src="{{ $testimonial->image }}" 
                                     alt="{{ $testimonial->name }}" 
                                     class="w-11 h-11 rounded-full object-cover border border-slate-200 shadow-2xs shrink-0">
                            @else
                                <div class="w-11 h-11 rounded-full bg-gradient-to-tr from-slate-800 to-indigo-600 text-white font-bold text-sm flex items-center justify-center border border-slate-200 shadow-2xs shrink-0">
                                    {{ strtoupper(substr($testimonial->name, 0, 2)) }}
                                </div>
                            @endif

                            <div class="min-w-0">
                                <h4 class="text-sm font-bold text-slate-900 truncate group-hover:text-indigo-600 transition-colors">
                                    {{ $testimonial->name }}
                                </h4>
                                <p class="text-xs text-slate-500 truncate mt-0.5">
                                    {{ $testimonial->company ?: 'Partner Client' }}
                                </p>
                            </div>
                        </div>

                        <!-- Featured Badge -->
                        @if ($testimonial->is_featured)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-800 border border-amber-200 shrink-0">
                                <svg class="w-3 h-3 text-amber-500 fill-amber-500" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <span>Featured</span>
                            </span>
                        @else
                            <span class="text-[11px] font-medium text-slate-400 font-mono px-2 py-0.5 rounded-md bg-slate-50 border border-slate-100 shrink-0">
                                Standard
                            </span>
                        @endif
                    </div>

                    <!-- Quote Content Body -->
                    <div class="relative bg-slate-50/80 rounded-xl p-3.5 border border-slate-100 text-xs text-slate-700 leading-relaxed">
                        <svg class="w-4 h-4 text-slate-300 absolute -top-2 left-3 fill-slate-200" viewBox="0 0 24 24">
                            <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                        </svg>
                        <p class="italic line-clamp-4">
                            "{{ $testimonial->content }}"
                        </p>

                        @if ($testimonial->content_id)
                            <div class="mt-2.5 pt-2 border-t border-slate-200/60 text-[11px] text-slate-500">
                                <span class="font-semibold text-slate-600">🇮🇩 ID:</span>
                                <span class="italic line-clamp-2">"{{ $testimonial->content_id }}"</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                    <span class="text-[11px] text-slate-400 font-mono">
                        Order #{{ $testimonial->order }}
                    </span>

                    <div class="flex items-center gap-1.5">
                        <a href="{{ route('admin.testimonials.edit', $testimonial) }}" 
                           class="btn btn-secondary py-1.5 px-3 text-xs font-semibold">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            <span>Edit</span>
                        </a>

                        <form action="{{ route('admin.testimonials.destroy', $testimonial) }}" 
                              method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this testimonial from {{ addslashes($testimonial->name) }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 border border-transparent hover:border-rose-200 transition-colors"
                                    title="Delete Testimonial"
                                    aria-label="Delete {{ $testimonial->name }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full card-modern text-center py-16">
                <div class="w-14 h-14 mx-auto rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-3">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                </div>
                <h4 class="text-sm font-bold text-slate-800">No Testimonials Found</h4>
                <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">Add your first partner or client review to showcase social proof on the website.</p>
                <div class="mt-4">
                    <a href="{{ route('admin.testimonials.create') }}" class="btn btn-primary text-xs py-2 px-4 shadow-2xs inline-flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Add Testimonial</span>
                    </a>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection