<!-- MODAL: Media Picker Modal -->
<div x-show="mediaModalOpen" 
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @keydown.escape.window="mediaModalOpen = false"
     class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4"
     role="dialog" 
     aria-modal="true" 
     aria-labelledby="modal-media-title"
     style="display: none;">
    
    <div class="bg-white rounded-2xl max-w-4xl w-full p-6 shadow-2xl border border-slate-200 max-h-[85vh] flex flex-col"
         @click.outside="mediaModalOpen = false">
        
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <h3 id="modal-media-title" class="text-base font-bold text-slate-900">Media Library Gallery</h3>
            </div>
            <button type="button" 
                    @click="mediaModalOpen = false" 
                    class="p-1.5 rounded-lg text-slate-500 hover:text-slate-700 hover:bg-slate-100 transition-colors"
                    aria-label="Close modal">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Upload Area -->
        <div class="py-3 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <span class="text-xs text-slate-600">Select an existing image from media library or upload a new file.</span>
            <label class="btn btn-secondary py-1.5 px-3 text-xs font-semibold flex items-center justify-center gap-1.5 cursor-pointer shrink-0">
                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                <span>Upload New Image</span>
                <input type="file" @change="uploadMediaFile($event)" accept="image/*" class="hidden">
            </label>
        </div>

        <!-- Media Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 gap-3 py-4 overflow-y-auto flex-1 min-h-[300px]">
            <template x-for="file in mediaFiles" :key="file.url">
                <button type="button" 
                        @click="selectMedia(file.url)" 
                        class="group relative rounded-xl border border-slate-200 overflow-hidden bg-slate-100 cursor-pointer hover:border-indigo-500 hover:shadow-md transition-all h-28 focus:ring-2 focus:ring-indigo-500 focus:outline-hidden text-left"
                        :aria-label="'Select image ' + file.name">
                    <img :src="file.url" :alt="file.name" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
                    <div class="absolute inset-0 bg-indigo-900/40 opacity-0 group-hover:opacity-100 group-focus:opacity-100 transition-opacity flex items-center justify-center text-white text-xs font-bold p-1 text-center">
                        Select Image
                    </div>
                </button>
            </template>
        </div>
    </div>
</div>
