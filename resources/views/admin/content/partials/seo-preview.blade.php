<div class="card-modern mb-6" x-data="{ seoOpen: false }">
    <button type="button" 
            class="w-full flex items-center justify-between cursor-pointer text-left focus:outline-hidden" 
            @click="seoOpen = !seoOpen"
            :aria-expanded="seoOpen"
            aria-controls="seo-settings-panel">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-indigo-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <h3 class="text-sm font-bold text-slate-800">Page SEO & Social Share Settings</h3>
            <span class="text-xs text-slate-500 font-mono hidden sm:inline">(Google Search Snippet & Social Card)</span>
        </div>
        <svg class="w-4 h-4 text-slate-500 transition-transform duration-200" :class="{'rotate-180': seoOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    <div id="seo-settings-panel" x-show="seoOpen" x-collapse class="mt-4 pt-4 border-t border-slate-100 space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="page-title-input" class="block text-xs font-semibold text-slate-700 mb-1">Page Title</label>
                <input type="text" 
                       name="title" 
                       id="page-title-input" 
                       x-model="pageTitle" 
                       @input="isDirty = true; queuePreviewUpdate()" 
                       class="text-xs w-full border border-slate-200 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500">
                <div class="flex justify-between text-[10px] text-slate-500 mt-1">
                    <span>Recommended max: 60 characters</span>
                    <span :class="pageTitle.length > 60 ? 'text-amber-600 font-bold' : 'text-slate-600'" x-text="pageTitle.length + '/60'"></span>
                </div>
            </div>

            <div>
                <label for="page-slug-input" class="block text-xs font-semibold text-slate-700 mb-1">Page Slug (URL Identifier)</label>
                <input type="text" 
                       name="slug" 
                       id="page-slug-input" 
                       value="{{ $page->slug }}" 
                       class="text-xs w-full border border-slate-200 rounded-lg p-2.5 bg-slate-50 text-slate-600 cursor-not-allowed" 
                       readonly>
            </div>
        </div>

        <div>
            <label for="page-meta-description-input" class="block text-xs font-semibold text-slate-700 mb-1">Meta Description</label>
            <textarea name="meta_description" 
                      id="page-meta-description-input" 
                      x-model="metaDescription" 
                      @input="isDirty = true" 
                      rows="2" 
                      class="text-xs w-full border border-slate-200 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500" 
                      placeholder="Brief summary of page content for search engines..."></textarea>
            <div class="flex justify-between text-[10px] text-slate-500 mt-1">
                <span>Recommended max: 160 characters</span>
                <span :class="metaDescription.length > 160 ? 'text-amber-600 font-bold' : 'text-slate-600'" x-text="metaDescription.length + '/160'"></span>
            </div>
        </div>

        <!-- Live Google & Social Share Previews -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
            <!-- Google Snippet -->
            <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl space-y-1">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Google Search Result Preview</span>
                <div class="text-xs font-semibold text-indigo-800 hover:underline truncate" x-text="(pageTitle || 'Page Title') + ' — Lima Biji Agritech'"></div>
                <div class="text-[11px] text-emerald-700 truncate" x-text="'https://limabiji.com/' + (slug || '')"></div>
                <div class="text-[11px] text-slate-700 line-clamp-2" x-text="metaDescription || 'No description provided yet.'"></div>
            </div>

            <!-- Social Card Preview -->
            <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl space-y-1">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Social Share Card Preview</span>
                <div class="border border-slate-200 rounded-lg overflow-hidden bg-white shadow-2xs">
                    <div class="h-20 bg-slate-200 flex items-center justify-center text-slate-500 text-xs font-semibold">
                        <svg class="w-6 h-6 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>Social Image Preview</span>
                    </div>
                    <div class="p-2 space-y-0.5">
                        <div class="text-[10px] uppercase font-bold text-slate-500">limabiji.com</div>
                        <div class="text-xs font-bold text-slate-800 truncate" x-text="pageTitle || 'Page Title'"></div>
                        <div class="text-[10px] text-slate-600 line-clamp-1" x-text="metaDescription || 'No description provided yet.'"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
