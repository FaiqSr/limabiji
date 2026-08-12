@extends('admin.layouts.app')
@section('title', 'Media Library')
@section('page_title', 'Media Library')

@section('content')
<div class="space-y-6">
    <div class="card-modern">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
            <h3 class="text-base font-semibold text-slate-900">Upload New File</h3>
            <span class="text-xs text-slate-500">Max 10 MB — JPG, PNG, WebP</span>
        </div>
        <form action="{{ route('admin.media.upload') }}" method="POST" enctype="multipart/form-data" class="flex items-end gap-4">
            @csrf
            <div class="flex-1">
                <label class="text-xs">Select Image</label>
                <input type="file" name="file" accept="image/*" required class="mt-1 text-sm">
            </div>
            <button type="submit" class="btn btn-primary py-2 px-6 text-sm">Upload</button>
        </form>
    </div>

    <div class="card-modern">
        <h3 class="text-base font-semibold text-slate-900 mb-4 pb-3 border-b border-slate-100">All Files ({{ count($files) }})</h3>
        @if ($files->isEmpty())
            <p class="text-sm text-slate-400 py-8 text-center">No files uploaded yet.</p>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach ($files as $file)
                    <div class="group relative rounded-lg border border-slate-200 bg-slate-50 overflow-hidden">
                        <img src="{{ $file['url'] }}" alt="{{ $file['name'] }}" class="w-full aspect-square object-cover">
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition-all flex items-center justify-center">
                            <button type="button" onclick="copyUrl('{{ $file['url'] }}')" class="opacity-0 group-hover:opacity-100 btn btn-secondary py-1 px-3 text-xs transition-all">
                                Copy URL
                            </button>
                        </div>
                        <div class="p-2">
                            <p class="text-[11px] text-slate-600 truncate">{{ $file['name'] }}</p>
                            <p class="text-[10px] text-slate-400">{{ round($file['size'] / 1024) }} KB</p>
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
            alert('URL copied: ' + url);
        });
    }
</script>
@endpush
@endsection