@extends('admin.layouts.app')
@section('title', 'Origins')
@section('page_title', 'Coffee Origins Management')

@section('content')
    <div class="space-y-6">
        <!-- Header Toolbar -->
        <div class="card-modern flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2.5">
                    <h3 class="text-base font-bold text-slate-900">Indonesian Coffee Origins</h3>
                    <span
                        class="text-xs font-semibold px-2 py-0.5 rounded-full bg-slate-100 text-slate-700 border border-slate-200">
                        {{ count($origins) }} {{ Str::plural('Region', count($origins)) }}
                    </span>
                </div>
                <p class="text-xs text-slate-500 mt-0.5">Manage specialty coffee partner regions, cupping scores, altitudes,
                    and processing methods.</p>
            </div>

            <a href="{{ route('admin.origins.create') }}"
                class="btn btn-primary text-xs py-2 px-4 shadow-2xs self-start sm:self-auto shrink-0 w-full sm:w-fit">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Add New Origin</span>
            </a>
        </div>

        <!-- Origins Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
            @forelse ($origins as $origin)
                <div
                    class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-2xs hover:shadow-md hover:border-slate-300 transition-all duration-200 flex flex-col group">
                    <!-- Cover Image & Overlay Badges -->
                    <div class="relative aspect-16/9 bg-slate-100 overflow-hidden">
                        @if ($origin->image)
                            <img src="{{ $origin->image }}" alt="{{ $origin->name }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div
                                class="w-full h-full flex flex-col items-center justify-center text-slate-400 gap-1 bg-slate-50">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span class="text-[11px] font-medium">No cover image</span>
                            </div>
                        @endif

                        <!-- Gradient Overlay -->
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-slate-900/70 via-transparent to-black/20 pointer-events-none">
                        </div>

                        <!-- Top Left: Status Badge -->
                        <div class="absolute top-3 left-3">
                            @if ($origin->is_active)
                                <span
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-500/90 text-white shadow-xs backdrop-blur-xs">
                                    <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                                    Active
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-800/80 text-slate-200 shadow-xs backdrop-blur-xs">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                    Inactive
                                </span>
                            @endif
                        </div>

                        <!-- Top Right: Cupping Score Badge -->
                        @if ($origin->score)
                            <div class="absolute top-3 right-3">
                                <span
                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-mono font-bold bg-white/95 text-slate-900 shadow-xs backdrop-blur-xs border border-white/40">
                                    <svg class="w-3.5 h-3.5 text-amber-500 fill-amber-500" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    <span>{{ $origin->score }} SCA</span>
                                </span>
                            </div>
                        @endif

                        <!-- Bottom Right: Gallery Counter -->
                        @php $galleryCount = count($origin->gallery ?: []); @endphp
                        @if ($galleryCount > 0)
                            <div class="absolute bottom-3 right-3">
                                <span
                                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-semibold bg-slate-900/75 text-white backdrop-blur-xs">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span>{{ $galleryCount }} {{ Str::plural('photo', $galleryCount) }}</span>
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Card Body -->
                    <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                        <div class="space-y-2.5">
                            <!-- Region Title & Province -->
                            <div>
                                <h4
                                    class="text-base font-bold text-slate-900 group-hover:text-emerald-600 transition-colors">
                                    {{ $origin->name }}
                                </h4>
                                <div class="flex items-center gap-1.5 text-xs text-slate-500 mt-0.5">
                                    <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span>{{ $origin->province }}</span>
                                    <span class="text-slate-300">•</span>
                                    <span class="font-mono text-[11px] text-slate-400">/{{ $origin->slug }}</span>
                                </div>
                            </div>

                            <!-- Origin Characteristic Chips -->
                            <div class="flex flex-wrap gap-1.5 pt-1">
                                @if ($origin->altitude)
                                    <span
                                        class="inline-flex items-center gap-1 text-[11px] font-mono px-2 py-0.5 rounded-md bg-slate-50 text-slate-600 border border-slate-200">
                                        <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 11l7-7 7 7M5 19l7-7 7 7" />
                                        </svg>
                                        {{ $origin->altitude }}
                                    </span>
                                @endif

                                @if ($origin->process)
                                    <span
                                        class="inline-flex items-center text-[11px] font-medium px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-100">
                                        {{ $origin->process }}
                                    </span>
                                @endif

                                @if ($origin->varietals)
                                    <span
                                        class="inline-flex items-center text-[11px] font-medium px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 border border-slate-200 truncate max-w-[200px]"
                                        title="{{ $origin->varietals }}">
                                        {{ $origin->varietals }}
                                    </span>
                                @endif
                            </div>

                            <!-- Overview Snippet -->
                            @if ($origin->overview)
                                <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed pt-1">
                                    {{ $origin->overview }}
                                </p>
                            @endif
                        </div>

                        <!-- Card Actions Footer -->
                        <div class="flex items-center justify-between pt-3 border-t border-slate-100 gap-2">
                            <a href="{{ route('landingpages.origins', $origin->slug) }}" target="_blank"
                                class="text-xs text-slate-500 hover:text-emerald-600 font-medium inline-flex items-center gap-1 transition-colors">
                                <span>View Live</span>
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </a>

                            <div class="flex items-center gap-1.5">
                                <a href="{{ route('admin.origins.edit', $origin) }}"
                                    class="btn btn-secondary py-1.5 px-3 text-xs font-semibold">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    <span>Edit</span>
                                </a>

                                <form action="{{ route('admin.origins.destroy', $origin) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete {{ addslashes($origin->name) }}? This will also remove associated images.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 border border-transparent hover:border-rose-200 transition-colors"
                                        title="Delete Origin" aria-label="Delete {{ $origin->name }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full card-modern text-center py-16">
                    <div
                        class="w-14 h-14 mx-auto rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-3">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <h4 class="text-sm font-bold text-slate-800">No Coffee Origins Registered</h4>
                    <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">Get started by creating your first regional
                        coffee origin profile.</p>
                    <div class="mt-4">
                        <a href="{{ route('admin.origins.create') }}"
                            class="btn btn-primary text-xs py-2 px-4 shadow-2xs inline-flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            <span>Create First Origin</span>
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection
