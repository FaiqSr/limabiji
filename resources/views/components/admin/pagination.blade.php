@props(['paginator', 'itemName' => 'articles'])

@if ($paginator->total() > 0)
    <div class="mt-8 flex flex-col md:flex-row items-center justify-between gap-4 card-modern p-4">
        <!-- Showing entries counter -->
        <div class="text-xs text-slate-500 font-medium order-2 md:order-1 flex items-center gap-2">
            <span>Showing <strong class="text-slate-800">{{ $paginator->firstItem() ?? 0 }}</strong> to <strong class="text-slate-800">{{ $paginator->lastItem() ?? 0 }}</strong> of <strong class="text-slate-800">{{ $paginator->total() }}</strong> {{ Str::plural($itemName, $paginator->total()) }}</span>
            
            <span class="text-slate-300 hidden sm:inline">•</span>
            <span class="text-slate-400 font-mono text-[11px] hidden sm:inline">Page {{ $paginator->currentPage() }} of {{ max(1, $paginator->lastPage()) }}</span>
        </div>

        <!-- Pagination Controls -->
        <div class="flex items-center gap-1.5 order-1 md:order-2 flex-wrap justify-center">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="px-3 py-1.5 text-xs font-semibold rounded-lg text-slate-300 bg-slate-50 border border-slate-200 cursor-not-allowed inline-flex items-center gap-1 select-none">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    <span>Prev</span>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" 
                   class="px-3 py-1.5 text-xs font-semibold rounded-lg text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 hover:border-slate-300 shadow-2xs transition-all inline-flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    <span>Prev</span>
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($paginator->getUrlRange(max(1, $paginator->currentPage() - 2), min($paginator->lastPage(), $paginator->currentPage() + 2)) as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="w-8 h-8 flex items-center justify-center text-xs font-bold rounded-lg bg-slate-900 text-white shadow-2xs">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $url }}" 
                       class="w-8 h-8 flex items-center justify-center text-xs font-semibold rounded-lg text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 hover:text-slate-900 shadow-2xs transition-all">
                        {{ $page }}
                    </a>
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" 
                   class="px-3 py-1.5 text-xs font-semibold rounded-lg text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 hover:border-slate-300 shadow-2xs transition-all inline-flex items-center gap-1">
                    <span>Next</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            @else
                <span class="px-3 py-1.5 text-xs font-semibold rounded-lg text-slate-300 bg-slate-50 border border-slate-200 cursor-not-allowed inline-flex items-center gap-1 select-none">
                    <span>Next</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </span>
            @endif
        </div>
    </div>
@endif
