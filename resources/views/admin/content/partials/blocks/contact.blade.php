<div class="space-y-3">
    <div>
        <x-cms.field-header label="Section Title" field="heading" :autoTranslate="true" />
        <input type="text" 
               :id="'block-' + index + '-' + loc + '-heading'"
               :name="'blocks['+index+'][content]['+loc+'][heading]'" 
               class="text-xs w-full border border-slate-200 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500" 
               x-model="content(block, loc).heading" 
               @input="isDirty = true; queuePreviewUpdate()">
    </div>
    <div>
        <x-cms.field-header label="Subtitle" field="subtitle" :autoTranslate="true" />
        <textarea :id="'block-' + index + '-' + loc + '-subtitle'"
                  :name="'blocks['+index+'][content]['+loc+'][subtitle]'" 
                  x-model="content(block, loc).subtitle" 
                  @input="isDirty = true; queuePreviewUpdate()" 
                  rows="2" 
                  class="w-full text-xs border border-slate-200 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500"></textarea>
    </div>
    <div class="grid grid-cols-2 gap-2">
        <div>
            <x-cms.field-header label="Export Email" field="email" />
            <input type="email" 
                   :id="'block-' + index + '-' + loc + '-email'"
                   :name="'blocks['+index+'][content]['+loc+'][email]'" 
                   class="text-xs w-full border border-slate-200 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500" 
                   x-model="content(block, loc).email" 
                   @input="isDirty = true; queuePreviewUpdate()">
        </div>
        <div>
            <x-cms.field-header label="Phone / WhatsApp" field="phone" />
            <input type="text" 
                   :id="'block-' + index + '-' + loc + '-phone'"
                   :name="'blocks['+index+'][content]['+loc+'][phone]'" 
                   class="text-xs w-full border border-slate-200 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500" 
                   x-model="content(block, loc).phone" 
                   @input="isDirty = true; queuePreviewUpdate()">
        </div>
    </div>
    <div>
        <x-cms.field-header label="Office Address" field="address" :autoTranslate="true" />
        <textarea :id="'block-' + index + '-' + loc + '-address'"
                  :name="'blocks['+index+'][content]['+loc+'][address]'" 
                  x-model="content(block, loc).address" 
                  @input="isDirty = true; queuePreviewUpdate()" 
                  rows="2" 
                  class="w-full text-xs border border-slate-200 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500"></textarea>
    </div>
</div>
