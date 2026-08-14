<!-- MODAL: Page Presets Modal -->
<div x-show="presetModalOpen" 
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @keydown.escape.window="presetModalOpen = false"
     class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4"
     role="dialog" 
     aria-modal="true" 
     aria-labelledby="modal-presets-title"
     style="display: none;">
    
    <div class="bg-white rounded-2xl max-w-xl w-full p-6 shadow-2xl border border-slate-200"
         @click.outside="presetModalOpen = false">
        
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div>
                <h3 id="modal-presets-title" class="text-base font-bold text-slate-900">Apply Page Layout Preset</h3>
                <p class="text-xs text-slate-600">Choose a predefined arrangement of blocks for rapid page building.</p>
            </div>
            <button type="button" 
                    @click="presetModalOpen = false" 
                    class="p-1.5 rounded-lg text-slate-500 hover:text-slate-700 hover:bg-slate-100 transition-colors"
                    aria-label="Close modal">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="space-y-3 py-4">
            <button type="button" 
                    @click="applyPreset('product_landing')" 
                    class="w-full text-left p-4 rounded-xl border border-slate-200 hover:border-indigo-500 hover:bg-indigo-50/50 cursor-pointer transition-all space-y-1 focus:ring-2 focus:ring-indigo-500 focus:outline-hidden">
                <div class="text-xs font-bold text-slate-900 flex items-center justify-between">
                    <span>Product Specialty Landing</span>
                    <span class="text-[10px] uppercase font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">5 Blocks</span>
                </div>
                <p class="text-[11px] text-slate-600">Includes Hero, Stats, Process Steps, Text+Stats, and Call to Action.</p>
            </button>

            <button type="button" 
                    @click="applyPreset('export_hub')" 
                    class="w-full text-left p-4 rounded-xl border border-slate-200 hover:border-indigo-500 hover:bg-indigo-50/50 cursor-pointer transition-all space-y-1 focus:ring-2 focus:ring-indigo-500 focus:outline-hidden">
                <div class="text-xs font-bold text-slate-900 flex items-center justify-between">
                    <span>Global Export & Partner Hub</span>
                    <span class="text-[10px] uppercase font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded">5 Blocks</span>
                </div>
                <p class="text-[11px] text-slate-600">Includes Hero, Export Map, Origins, FAQ, and Call to Action.</p>
            </button>

            <button type="button" 
                    @click="applyPreset('news_story')" 
                    class="w-full text-left p-4 rounded-xl border border-slate-200 hover:border-indigo-500 hover:bg-indigo-50/50 cursor-pointer transition-all space-y-1 focus:ring-2 focus:ring-indigo-500 focus:outline-hidden">
                <div class="text-xs font-bold text-slate-900 flex items-center justify-between">
                    <span>News & Brand Story Page</span>
                    <span class="text-[10px] uppercase font-bold text-purple-600 bg-purple-50 px-2 py-0.5 rounded">4 Blocks</span>
                </div>
                <p class="text-[11px] text-slate-600">Includes Hero, Text Section, Articles Grid, and Testimonials.</p>
            </button>
        </div>
    </div>
</div>
