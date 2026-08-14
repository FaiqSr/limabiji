<div class="space-y-3">
    <div>
        <x-cms.field-header label="Section Heading" field="heading" :autoTranslate="true" />
        <input type="text" 
               :id="'block-' + index + '-' + loc + '-heading'"
               :name="'blocks['+index+'][content]['+loc+'][heading]'" 
               class="text-xs w-full border border-slate-200 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500" 
               x-model="content(block, loc).heading" 
               @input="isDirty = true; queuePreviewUpdate()">
    </div>
    <div class="space-y-2">
        <label class="text-xs font-semibold text-slate-700">FAQ Items (Repeater)</label>
        <template x-for="(item, itemIndex) in items(block, loc)" :key="itemIndex">
            <div class="space-y-2 rounded-lg border border-slate-200 bg-slate-50 p-3">
                <div class="flex items-center justify-between gap-2">
                    <input type="text" 
                           :id="'block-' + index + '-' + loc + '-faq-q-' + itemIndex"
                           :name="'blocks['+index+'][content]['+loc+'][items]['+itemIndex+'][question]'" 
                           x-model="item.question" 
                           @input="isDirty = true; queuePreviewUpdate()" 
                           class="text-xs flex-1 border border-slate-200 rounded p-1.5 bg-white focus:ring-2 focus:ring-indigo-500" 
                           placeholder="Question"
                           :aria-label="'FAQ Question ' + (itemIndex + 1)">
                    <button type="button" 
                            @click="removeItem(block, loc, itemIndex)" 
                            class="text-xs text-rose-600 font-bold px-2 py-1 hover:bg-rose-50 rounded transition-colors" 
                            aria-label="Remove FAQ item">✕</button>
                </div>
                <textarea :id="'block-' + index + '-' + loc + '-faq-a-' + itemIndex"
                          :name="'blocks['+index+'][content]['+loc+'][items]['+itemIndex+'][answer]'" 
                          x-model="item.answer" 
                          @input="isDirty = true; queuePreviewUpdate()" 
                          rows="2" 
                          class="w-full text-xs border border-slate-200 rounded p-2 bg-white focus:ring-2 focus:ring-indigo-500" 
                          placeholder="Answer..."
                          :aria-label="'FAQ Answer ' + (itemIndex + 1)"></textarea>
            </div>
        </template>
        <button type="button" 
                @click="addItem(block, loc, { question: '', answer: '' })" 
                class="btn btn-secondary py-1 px-3 text-xs">+ Add FAQ Item</button>
    </div>
</div>
