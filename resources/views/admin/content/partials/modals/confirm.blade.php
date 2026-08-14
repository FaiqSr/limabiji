<!-- MODAL: Custom Async Confirmation Modal -->
<div x-show="confirmModal.open" 
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @keydown.escape.window="cancelConfirm()"
     class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4"
     role="dialog" 
     aria-modal="true" 
     aria-labelledby="modal-confirm-title"
     style="display: none;">
    
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-200"
         @click.outside="cancelConfirm()">
        
        <div class="flex items-start gap-3">
            <div class="p-2.5 rounded-full shrink-0" 
                 :class="confirmModal.isDanger ? 'bg-rose-50 text-rose-600 border border-rose-100' : 'bg-indigo-50 text-indigo-600 border border-indigo-100'">
                <template x-if="confirmModal.isDanger">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </template>
                <template x-if="!confirmModal.isDanger">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </template>
            </div>

            <div class="flex-1 space-y-1">
                <h3 id="modal-confirm-title" class="text-base font-bold text-slate-900" x-text="confirmModal.title || 'Confirm Action'"></h3>
                <p class="text-xs text-slate-600 leading-relaxed" x-text="confirmModal.message || 'Are you sure you want to proceed?'"></p>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end gap-2.5 border-t border-slate-100 pt-4">
            <button type="button" 
                    @click="cancelConfirm()" 
                    :disabled="confirmModal.isProcessing"
                    class="px-3.5 py-2 text-xs font-semibold rounded-lg text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 transition-colors focus:ring-2 focus:ring-slate-400 focus:outline-hidden disabled:opacity-50 disabled:cursor-not-allowed">
                Cancel
            </button>
            <button type="button" 
                    @click="executeConfirm()" 
                    :disabled="confirmModal.isProcessing"
                    class="px-4 py-2 text-xs font-bold rounded-lg text-white transition-all shadow-2xs flex items-center gap-1.5 focus:ring-2 focus:outline-hidden disabled:opacity-50 disabled:cursor-not-allowed"
                    :class="confirmModal.isDanger ? 'bg-rose-600 hover:bg-rose-700 focus:ring-rose-500' : 'bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500'">
                <svg x-show="confirmModal.isProcessing" class="w-3.5 h-3.5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <span x-text="confirmModal.confirmText || 'Confirm'"></span>
            </button>
        </div>
    </div>
</div>
