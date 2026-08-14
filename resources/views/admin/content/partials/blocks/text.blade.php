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
        <x-cms.field-header label="Body Content" field="body" :autoTranslate="true" />
        <textarea :id="'block-' + index + '-' + loc + '-body'"
                  :name="'blocks['+index+'][content]['+loc+'][body]'" 
                  x-model="content(block, loc).body" 
                  @input="isDirty = true; queuePreviewUpdate()" 
                  rows="4" 
                  class="w-full text-xs border border-slate-200 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500"></textarea>
    </div>
</div>
