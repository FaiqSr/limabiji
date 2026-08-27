@props([
    'name' => 'gallery',
    'value' => [],
    'context' => 'origins',
    'label' => 'Origin Gallery Images',
    'hint' => 'Upload multiple photos. Max 5MB per file (JPG, PNG, WebP).'
])

@php
$initialImages = old($name, $value ?: []);
if (is_string($initialImages)) {
    $decoded = json_decode($initialImages, true);
    $initialImages = is_array($decoded) ? $decoded : [];
}
if (!is_array($initialImages)) {
    $initialImages = [];
}
$initialImages = array_values(array_filter($initialImages));
@endphp

<div
    x-data="{
        images: @js($initialImages),
        uploading: false,
        uploadProgress: '',
        error: null,
        isDragging: false,

        handleFiles(files) {
            if (!files || files.length === 0) return;
            this.uploading = true;
            this.error = null;
            let total = files.length;
            let completed = 0;

            const uploadOne = (file) => {
                const formData = new FormData();
                formData.append('file', file);
                formData.append('context', '{{ $context }}');

                return fetch('{{ route('admin.media.upload') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: formData,
                })
                .then(async (res) => {
                    const contentType = res.headers.get('content-type') || '';
                    let data = null;
                    if (contentType.includes('application/json')) {
                        try {
                            data = await res.json();
                        } catch (_) {
                            data = null;
                        }
                    }
                    return { ok: res.ok, status: res.status, data };
                })
                .then(({ ok, status, data }) => {
                    if (ok && data && data.success) {
                        this.images = [...this.images, data.url];
                    } else if (data && (data.message || (data.errors && data.errors.file))) {
                        this.error = (data.errors && data.errors.file) ? data.errors.file[0] : data.message;
                    } else if (status === 419) {
                        this.error = 'Session expired. Please refresh the page and try again.';
                    } else if (status === 413) {
                        this.error = 'File is too large. Maximum allowed size is 10MB.';
                    } else {
                        this.error = 'Upload failed (HTTP ' + status + '). Please try another image.';
                    }
                })
                .catch(err => {
                    this.error = 'Upload error: ' + err.message;
                })
                .finally(() => {
                    completed++;
                    this.uploadProgress = `${completed}/${total}`;
                    if (completed === total) {
                        this.uploading = false;
                        this.uploadProgress = '';
                    }
                });
            };

            Array.from(files).forEach(file => uploadOne(file));
        },

        removeImage(index) {
            this.images = this.images.filter((_, i) => i !== index);
        },

        moveImage(index, direction) {
            const targetIndex = index + direction;
            if (targetIndex < 0 || targetIndex >= this.images.length) return;
            const newImages = [...this.images];
            const temp = newImages[index];
            newImages[index] = newImages[targetIndex];
            newImages[targetIndex] = temp;
            this.images = newImages;
        }
    }"
    class="space-y-3"
>
    <div class="flex items-center justify-between">
        <label class="block text-sm font-semibold text-slate-900">{{ $label }}</label>
        <span class="text-xs text-slate-500 font-medium" x-text="images.length + ' image(s)'"></span>
    </div>

    {{-- Hidden form inputs to send array to controller --}}
    <template x-for="(url, i) in images" :key="i">
        <input type="hidden" :name="'{{ $name }}[' + i + ']'" :value="url">
    </template>

    {{-- Dropzone & Upload Button --}}
    <div
        @dragover.prevent="isDragging = true"
        @dragleave.prevent="isDragging = false"
        @drop.prevent="isDragging = false; handleFiles($event.dataTransfer.files)"
        :class="isDragging ? 'border-emerald-500 bg-emerald-50/50' : 'border-slate-300 bg-slate-50/50 hover:bg-slate-100/50'"
        class="border-2 border-dashed rounded-lg p-5 text-center transition-colors cursor-pointer relative"
    >
        <input
            type="file"
            multiple
            accept="image/jpeg,image/jpg,image/png,image/webp"
            @change="handleFiles($event.target.files)"
            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
            :disabled="uploading"
        >

        <div class="flex flex-col items-center justify-center gap-2 pointer-events-none">
            <div class="w-10 h-10 rounded-lg bg-white shadow-2xs border border-slate-200 flex items-center justify-center text-slate-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-700">
                    <span x-show="!uploading">Click to upload or drag and drop photos</span>
                    <span x-show="uploading" class="inline-flex items-center gap-1.5 text-slate-900">
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Uploading photos (<span x-text="uploadProgress"></span>)...
                    </span>
                </p>
                <p class="text-[11px] text-slate-400 mt-0.5">{{ $hint }}</p>
            </div>
        </div>
    </div>

    <p x-show="error" x-cloak x-text="error" class="text-rose-600 text-xs mt-1 font-medium"></p>

    {{-- Gallery Grid Preview --}}
    <div x-show="images.length > 0" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 pt-2">
        <template x-for="(url, index) in images" :key="index">
            <div class="group relative bg-slate-100 rounded-lg overflow-hidden border border-slate-200 aspect-4/3 shadow-2xs">
                <img :src="url" alt="Gallery item" class="w-full h-full object-cover">

                <!-- Hover Overlay Controls -->
                <div class="absolute inset-0 bg-slate-900/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-1.5 p-2">
                    <button
                        type="button"
                        @click="moveImage(index, -1)"
                        :disabled="index === 0"
                        class="p-1.5 rounded-lg bg-white/90 hover:bg-white text-slate-700 disabled:opacity-30 transition-all text-xs"
                        title="Move left/up"
                    >
                        ←
                    </button>
                    <button
                        type="button"
                        @click="moveImage(index, 1)"
                        :disabled="index === images.length - 1"
                        class="p-1.5 rounded-lg bg-white/90 hover:bg-white text-slate-700 disabled:opacity-30 transition-all text-xs"
                        title="Move right/down"
                    >
                        →
                    </button>
                    <button
                        type="button"
                        @click="removeImage(index)"
                        class="p-1.5 rounded-lg bg-rose-600 hover:bg-rose-700 text-white transition-all text-xs font-bold"
                        title="Remove photo"
                    >
                        ✕
                    </button>
                </div>

                <span class="absolute bottom-1 left-1 bg-slate-900/70 text-white text-[10px] font-mono px-1.5 py-0.5 rounded-lg" x-text="'#' + (index + 1)"></span>
            </div>
        </template>
    </div>
</div>
