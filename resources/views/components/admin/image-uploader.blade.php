@props(['name' => 'image', 'value' => '', 'context' => 'media', 'label' => 'Featured Image', 'hint' => 'Max 5MB. Supported: JPG, PNG, WebP.'])

@php
$initial = old($name, $value);
@endphp

<div
    x-data="{
        imageUrl: '{{ $initial }}',
        uploading: false,
        error: null,
        uploadFile(event) {
            const file = event.target.files[0];
            if (!file) return;
            this.uploading = true;
            this.error = null;

            const formData = new FormData();
            formData.append('file', file);
            formData.append('context', '{{ $context }}');

            fetch('{{ route('admin.media.upload') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: formData,
            })
            .then(async (response) => {
                const contentType = response.headers.get('content-type') || '';
                let data = null;
                if (contentType.includes('application/json')) {
                    try {
                        data = await response.json();
                    } catch (_) {
                        data = null;
                    }
                }
                return { ok: response.ok, status: response.status, data };
            })
            .then(({ ok, status, data }) => {
                if (ok && data && data.success) {
                    this.imageUrl = data.url;
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
                this.uploading = false;
            });
        },
        clearImage() {
            this.imageUrl = '';
            this.$refs.fileInput.value = '';
        },
    }"
>
    <label class="block text-sm font-semibold text-slate-700 mb-2">{{ $label }}</label>

    {{-- Hidden input carries final image URL --}}
    <input type="hidden" name="{{ $name }}" x-model="imageUrl">

    {{-- Preview --}}
    <div x-show="imageUrl" x-cloak class="mb-4">
        <div class="relative inline-block w-full">
            <img :src="imageUrl" alt="Preview" class="w-full h-36 object-cover rounded-lg border border-slate-200 shadow-sm ">
            <button type="button" @click="clearImage"
                class="absolute -top-2 -right-2 bg-rose-500 text-white rounded-lg w-6 h-6 flex items-center justify-center hover:bg-rose-600 transition-colors shadow-2xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Upload button --}}
    <div class="flex flex-wrap items-center gap-3 w-full">
        <label class="btn btn-secondary text-xs py-2 px-4 cursor-pointer inline-flex items-center gap-2 mb-0 w-full">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
            </svg>
            <span x-show="!uploading">Upload Image</span>
            <span x-show="uploading">
                <svg class="w-4 h-4 inline animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                Uploading...
            </span>
            <input type="file" @change="uploadFile" accept="image/jpeg,image/jpg,image/png,image/webp" x-ref="fileInput" class="hidden">
        </label>
    </div>

    <p x-show="error" x-cloak x-text="error" class="text-rose-600 text-xs mt-2"></p>
    <p class="text-xs text-slate-500 mt-1">{{ $hint }}</p>
</div>
