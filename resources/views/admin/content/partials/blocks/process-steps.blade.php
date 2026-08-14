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
    <div>
        <x-cms.field-header label="Subtitle" field="subtitle" :autoTranslate="true" />
        <input type="text" 
               :id="'block-' + index + '-' + loc + '-subtitle'"
               :name="'blocks['+index+'][content]['+loc+'][subtitle]'" 
               class="text-xs w-full border border-slate-200 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500" 
               x-model="content(block, loc).subtitle" 
               @input="isDirty = true; queuePreviewUpdate()">
    </div>
    <div class="space-y-2">
        <label class="text-xs font-semibold text-slate-700">Process Steps (Repeater)</label>
        <template x-for="(item, itemIndex) in items(block, loc)" :key="itemIndex">
            <div class="space-y-2 rounded-lg border border-slate-200 bg-slate-50 p-3">
                <div class="grid grid-cols-2 gap-2">
                    <input type="text" 
                           :id="'block-' + index + '-' + loc + '-step-num-' + itemIndex"
                           :name="'blocks['+index+'][content]['+loc+'][items]['+itemIndex+'][step]'" 
                           x-model="item.step" 
                           @input="isDirty = true; queuePreviewUpdate()" 
                           class="text-xs border border-slate-200 rounded p-1.5 bg-white focus:ring-2 focus:ring-indigo-500" 
                           placeholder="Step # (01)"
                           :aria-label="'Step number ' + (itemIndex + 1)">
                    <input type="text" 
                           :id="'block-' + index + '-' + loc + '-step-title-' + itemIndex"
                           :name="'blocks['+index+'][content]['+loc+'][items]['+itemIndex+'][title]'" 
                           x-model="item.title" 
                           @input="isDirty = true; queuePreviewUpdate()" 
                           class="text-xs border border-slate-200 rounded p-1.5 bg-white focus:ring-2 focus:ring-indigo-500" 
                           placeholder="Title"
                           :aria-label="'Step title ' + (itemIndex + 1)">
                </div>
                
                <!-- Image URL + Media Picker Button -->
                <div class="flex items-center gap-1.5">
                    <input type="text" 
                           :id="'block-' + index + '-' + loc + '-step-img-' + itemIndex"
                           :name="'blocks['+index+'][content]['+loc+'][items]['+itemIndex+'][image]'" 
                           x-model="item.image" 
                           @input="isDirty = true; queuePreviewUpdate()" 
                           class="text-xs flex-1 border border-slate-200 rounded p-1.5 bg-white focus:ring-2 focus:ring-indigo-500" 
                           placeholder="Image URL"
                           :aria-label="'Step image URL ' + (itemIndex + 1)">
                    <button type="button" 
                            @click="openMediaPicker(index, loc, 'items.' + itemIndex + '.image')" 
                            class="btn btn-secondary py-1.5 px-2 text-xs flex items-center gap-1"
                            aria-label="Pick media for step image">
                        <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>Media</span>
                    </button>
                </div>

                <textarea :id="'block-' + index + '-' + loc + '-step-desc-' + itemIndex"
                          :name="'blocks['+index+'][content]['+loc+'][items]['+itemIndex+'][description]'" 
                          x-model="item.description" 
                          @input="isDirty = true; queuePreviewUpdate()" 
                          rows="2" 
                          class="w-full text-xs border border-slate-200 rounded p-2 bg-white focus:ring-2 focus:ring-indigo-500" 
                          placeholder="Description..."
                          :aria-label="'Step description ' + (itemIndex + 1)"></textarea>
                <input type="text" 
                       :id="'block-' + index + '-' + loc + '-step-det-' + itemIndex"
                       :name="'blocks['+index+'][content]['+loc+'][items]['+itemIndex+'][details]'" 
                       x-model="item.details" 
                       @input="isDirty = true; queuePreviewUpdate()" 
                       class="text-xs w-full border border-slate-200 rounded p-1.5 bg-white focus:ring-2 focus:ring-indigo-500" 
                       placeholder="Details (comma-separated tags)"
                       :aria-label="'Step details ' + (itemIndex + 1)">
                <button type="button" 
                        @click="removeItem(block, loc, itemIndex)" 
                        class="text-xs text-rose-600 font-bold hover:underline"
                        aria-label="Remove this step">Remove Step</button>
            </div>
        </template>
        <button type="button" 
                @click="addItem(block, loc, { step: '', title: '', image: '', description: '', details: '' })" 
                class="btn btn-secondary py-1 px-3 text-xs">+ Add Step</button>
    </div>
</div>
