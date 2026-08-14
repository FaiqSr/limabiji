@props([
    'label',
    'field',
    'autoTranslate' => false,
    'required'      => false,
    'blockVar'      => 'block',
    'locVar'        => 'loc',
    'indexVar'      => 'index',
])

<div class="flex items-center justify-between mb-1 gap-2">
    <label :for="'block-' + {{ $indexVar }} + '-' + {{ $locVar }} + '-{{ $field }}'" class="text-xs font-semibold text-slate-700 truncate">
        {{ $label }}
        @if($required)
            <span class="text-rose-500">*</span>
        @endif
    </label>
    <div class="flex items-center gap-1.5 shrink-0">
        <button type="button" 
                @click="toggleFieldHidden({{ $blockVar }}, {{ $locVar }}, '{{ $field }}')" 
                class="text-[10px] font-semibold px-1.5 py-0.5 rounded border flex items-center gap-1 transition-colors" 
                :class="isFieldHidden({{ $blockVar }}, {{ $locVar }}, '{{ $field }}') ? 'bg-rose-50 text-rose-600 border-rose-200' : 'bg-slate-50 text-slate-600 border-slate-200 hover:text-indigo-600'" 
                :title="isFieldHidden({{ $blockVar }}, {{ $locVar }}, '{{ $field }}') ? 'Field is hidden on website' : 'Field is visible on website'"
                :aria-label="isFieldHidden({{ $blockVar }}, {{ $locVar }}, '{{ $field }}') ? 'Show field ' + '{{ $label }}' : 'Hide field ' + '{{ $label }}'">
            <svg x-show="!isFieldHidden({{ $blockVar }}, {{ $locVar }}, '{{ $field }}')" class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            <svg x-show="isFieldHidden({{ $blockVar }}, {{ $locVar }}, '{{ $field }}')" class="w-3 h-3 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a8.88 8.88 0 012.122-.363c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21M3 3l18 18"/></svg>
            <span x-text="isFieldHidden({{ $blockVar }}, {{ $locVar }}, '{{ $field }}') ? 'Hidden' : 'Visible'"></span>
        </button>
        @if($autoTranslate)
            <button type="button" 
                    @click="autoTranslate({{ $blockVar }}, {{ $locVar }}, '{{ $field }}')" 
                    class="text-[10px] font-semibold text-indigo-600 hover:text-indigo-800 flex items-center gap-1"
                    aria-label="Auto translate {{ $label }}">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                <span>Auto-Translate</span>
            </button>
        @endif
    </div>
</div>
<input type="hidden" :name="'blocks['+{{ $indexVar }}+'][content]['+{{ $locVar }}+'][field_hidden][{{ $field }}]'" :value="isFieldHidden({{ $blockVar }}, {{ $locVar }}, '{{ $field }}') ? 1 : 0">
