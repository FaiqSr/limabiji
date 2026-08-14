@extends('admin.layouts.app')
@section('title', 'Media Library')
@section('page_title', 'Media Library')

@section('content')
<div class="space-y-6">
    @if (session('success'))
        <div class="p-4 rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-semibold">
            ✓ {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="p-4 rounded-lg bg-rose-50 text-rose-800 border border-rose-200 text-xs font-semibold">
            ✕ {{ session('error') }}
        </div>
    @endif

    <!-- Upload Form Card -->
    <div class="card-modern">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
            <div>
                <h3 class="text-base font-semibold text-slate-900">Upload New File</h3>
                <p class="text-xs text-slate-500">Max 10 MB — Supported formats: JPG, PNG, WebP, SVG, GIF</p>
            </div>
            <span class="text-xs font-mono px-2.5 py-1 rounded bg-slate-100 text-slate-600 border border-slate-200">Storage: Public Disk</span>
        </div>
        <form action="{{ route('admin.media.upload') }}" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row sm:items-end gap-4">
            @csrf
            <div class="flex-1">
                <label class="block text-xs font-semibold text-slate-700 mb-1">Select File</label>
                <input type="file" name="file" accept="image/*" required class="text-xs w-full border border-slate-200 rounded-lg p-2 bg-slate-50">
            </div>
            <div class="w-48">
                <label class="block text-xs font-semibold text-slate-700 mb-1">Upload Folder</label>
                <select name="context" class="text-xs w-full border border-slate-200 rounded-lg p-2.5 bg-slate-50">
                    <option value="media">media/</option>
                    <option value="articles">articles/</option>
                    <option value="origins">origins/</option>
                    <option value="general">general/</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary py-2.5 px-6 text-xs font-bold shadow-2xs">
                Upload File
            </button>
        </form>
    </div>

    <!-- Media Library Grid Card -->
    <div class="card-modern">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
            <h3 class="text-base font-semibold text-slate-900">All Storage Files ({{ count($files) }})</h3>
            <span class="text-xs text-slate-400">Click any image card to copy its URL or manage file.</span>
        </div>

        @if ($files->isEmpty())
            <div class="py-12 text-center space-y-2">
                <svg class="w-12 h-12 text-slate-300 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-xs text-slate-400 font-medium">No media files found in public storage.</p>
            </div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach ($files as $file)
                    <div class="group relative rounded-xl border border-slate-200 bg-white overflow-hidden shadow-2xs flex flex-col justify-between">
                        <div class="relative aspect-square bg-slate-100 overflow-hidden">
                            <img src="{{ $file['url'] }}" alt="{{ $file['name'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            
                            <!-- Hover Action Overlay -->
                            <div class="absolute inset-0 bg-slate-900/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center p-2 gap-2">
                                <button type="button" onclick="copyUrl('{{ $file['url'] }}')" class="w-full btn btn-secondary py-1 px-2 text-[11px] font-bold shadow-xs">
                                    Copy URL
                                </button>
                                <form action="{{ route('admin.media.destroy') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this file permanently?');" class="w-full">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="path" value="{{ $file['path'] }}">
                                    <button type="submit" class="w-full btn btn-danger py-1 px-2 text-[11px] font-bold shadow-xs">
                                        Delete File
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="p-2.5 bg-slate-50/80 border-t border-slate-100">
                            <p class="text-[11px] font-bold text-slate-800 truncate" title="{{ $file['name'] }}">{{ $file['name'] }}</p>
                            <div class="flex items-center justify-between mt-1 text-[10px] text-slate-400 font-mono">
                                <span>{{ round($file['size'] / 1024) }} KB</span>
                                <span class="truncate max-w-[80px]">{{ dirname($file['path']) }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    function copyUrl(url) {
        navigator.clipboard.writeText(url).then(() => {
            alert('Image URL copied to clipboard:\n' + url);
        });
    }
</script>
@endpush
@endsection