<div class="space-y-3">
    <div>
        <x-cms.field-header label="Heading" field="heading" :autoTranslate="true" />
        <input type="text" 
               :id="'block-' + index + '-' + loc + '-heading'"
               :name="'blocks['+index+'][content]['+loc+'][heading]'" 
               class="text-xs w-full border border-slate-200 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500" 
               x-model="content(block, loc).heading" 
               @input="isDirty = true; queuePreviewUpdate()">
    </div>
    <div class="space-y-2">
        <label class="text-xs font-semibold text-slate-700">Stat Items (Repeater)</label>
        <template x-for="(item, itemIndex) in items(block, loc)" :key="itemIndex">
            <div class="grid grid-cols-[1fr_1fr_auto] gap-2 items-center bg-slate-50 p-2 rounded-lg border border-slate-200">
                <input type="text" 
                       :id="'block-' + index + '-' + loc + '-stat-l-' + itemIndex"
                       :name="'blocks['+index+'][content]['+loc+'][items]['+itemIndex+'][label]'" 
                       x-model="item.label" 
                       @input="isDirty = true; queuePreviewUpdate()" 
                       class="text-xs border border-slate-200 rounded p-1.5 bg-white focus:ring-2 focus:ring-indigo-500" 
                       placeholder="Label"
                       :aria-label="'Stat Label ' + (itemIndex + 1)">
                <input type="text" 
                       :id="'block-' + index + '-' + loc + '-stat-v-' + itemIndex"
                       :name="'blocks['+index+'][content]['+loc+'][items]['+itemIndex+'][value]'" 
                       x-model="item.value" 
                       @input="isDirty = true; queuePreviewUpdate()" 
                       class="text-xs border border-slate-200 rounded p-1.5 bg-white focus:ring-2 focus:ring-indigo-500" 
                       placeholder="Value (e.g. 7+)"
                       :aria-label="'Stat Value ' + (itemIndex + 1)">
                <button type="button" 
                        @click="removeItem(block, loc, itemIndex)" 
                        class="text-xs text-rose-600 font-bold px-2 py-1 hover:bg-rose-50 rounded transition-colors" 
                        aria-label="Remove stat item">✕</button>
            </div>
        </template>
        <button type="button" 
                @click="addItem(block, loc, { label: '', value: '' })" 
                class="btn btn-secondary py-1 px-3 text-xs">+ Add Stat Item</button>
    </div>
</div>
