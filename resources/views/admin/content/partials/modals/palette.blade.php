<!-- MODAL: Add Block Visual Palette Modal -->
<div x-show="paletteModalOpen" 
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @keydown.escape.window="paletteModalOpen = false"
     class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4"
     role="dialog" 
     aria-modal="true" 
     aria-labelledby="modal-palette-title"
     style="display: none;">
    
    <div class="bg-white rounded-2xl max-w-3xl w-full p-6 shadow-2xl border border-slate-200 max-h-[85vh] flex flex-col"
         @click.outside="paletteModalOpen = false">
        
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div>
                <h3 id="modal-palette-title" class="text-base font-bold text-slate-900">Add Content Block Component</h3>
                <p class="text-xs text-slate-600">Choose a pre-designed layout section to add to your page.</p>
            </div>
            <button type="button" 
                    @click="paletteModalOpen = false" 
                    class="p-1.5 rounded-lg text-slate-500 hover:text-slate-700 hover:bg-slate-100 transition-colors"
                    aria-label="Close modal">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 py-4 overflow-y-auto flex-1">
            <template x-for="blockType in availableBlockTypes" :key="blockType.type">
                <button type="button" 
                        @click="addBlock(blockType.type); paletteModalOpen = false" 
                        class="text-left p-3.5 rounded-xl border border-slate-200 hover:border-indigo-500 hover:shadow-md cursor-pointer transition-all bg-slate-50/50 hover:bg-white group flex flex-col justify-between focus:ring-2 focus:ring-indigo-500 focus:outline-hidden">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold uppercase tracking-wider text-indigo-700 group-hover:text-indigo-600" x-text="blockType.label"></span>
                            <svg class="w-4 h-4 text-slate-400 group-hover:text-indigo-600 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        <p class="text-[11px] text-slate-600 leading-relaxed" x-text="blockType.desc"></p>
                    </div>
                </button>
            </template>
        </div>
    </div>
</div>
