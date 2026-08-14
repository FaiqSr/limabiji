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
        <x-cms.field-header label="Body" field="body" :autoTranslate="true" />
        <textarea :id="'block-' + index + '-' + loc + '-body'"
                  :name="'blocks['+index+'][content]['+loc+'][body]'" 
                  x-model="content(block, loc).body" 
                  @input="isDirty = true; queuePreviewUpdate()" 
                  rows="2" 
                  class="w-full text-xs border border-slate-200 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500"></textarea>
    </div>
    <div class="grid grid-cols-2 gap-2">
        <div>
            <x-cms.field-header label="Button Text" field="button_text" :autoTranslate="true" />
            <input type="text" 
                   :id="'block-' + index + '-' + loc + '-button_text'"
                   :name="'blocks['+index+'][content]['+loc+'][button_text]'" 
                   class="text-xs w-full border border-slate-200 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500" 
                   x-model="content(block, loc).button_text" 
                   @input="isDirty = true; queuePreviewUpdate()">
        </div>
        <div>
            <x-cms.field-header label="Button URL" field="button_url" />
            <input type="text" 
                   :id="'block-' + index + '-' + loc + '-button_url'"
                   :name="'blocks['+index+'][content]['+loc+'][button_url]'" 
                   class="text-xs w-full border border-slate-200 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500" 
                   x-model="content(block, loc).button_url" 
                   @input="isDirty = true; queuePreviewUpdate()">
        </div>
    </div>
</div>
