<!-- Toast Notification Feedback -->
<div x-show="toast.open" 
     x-transition:enter="transition ease-out duration-300 transform"
     x-transition:enter-start="opacity-0 translate-y-2"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-200 transform"
     x-transition:leave-start="opacity-100 translate-y-0"
     x-transition:leave-end="opacity-0 translate-y-2"
     role="status"
     aria-live="polite"
     class="fixed top-6 right-6 z-50 flex items-center gap-2.5 px-4 py-3 rounded-xl shadow-xl text-xs font-semibold border"
     :class="{
         'bg-emerald-50 text-emerald-800 border-emerald-200': toast.type === 'success',
         'bg-rose-50 text-rose-800 border-rose-200': toast.type === 'error',
         'bg-indigo-50 text-indigo-800 border-indigo-200': toast.type === 'info',
         'bg-amber-50 text-amber-800 border-amber-200': toast.type === 'warning'
     }"
     style="display: none;">
    
    <!-- Success Icon -->
    <svg x-show="toast.type === 'success'" class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
    </svg>

    <!-- Error Icon -->
    <svg x-show="toast.type === 'error'" class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
    </svg>

    <!-- Info Icon -->
    <svg x-show="toast.type === 'info'" class="w-4 h-4 text-indigo-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>

    <!-- Warning Icon -->
    <svg x-show="toast.type === 'warning'" class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
    </svg>

    <span x-text="toast.message"></span>

    <button type="button" @click="toast.open = false" class="ml-2 text-slate-400 hover:text-slate-600" aria-label="Close notification">✕</button>
</div>
