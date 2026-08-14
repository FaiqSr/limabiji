<div class="space-y-3">
    <div>
        <x-cms.field-header label="Badge Label" field="label" />
        <input type="text" 
               :id="'block-' + index + '-' + loc + '-label'"
               :name="'blocks['+index+'][content]['+loc+'][label]'" 
               class="text-xs w-full border border-slate-200 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500" 
               x-model="content(block, loc).label" 
               @input="isDirty = true; queuePreviewUpdate()">
    </div>
    <div>
        <x-cms.field-header label="Main Heading (supports <br>)" field="heading" :autoTranslate="true" />
        <input type="text" 
               :id="'block-' + index + '-' + loc + '-heading'"
               :name="'blocks['+index+'][content]['+loc+'][heading]'" 
               class="text-xs w-full border border-slate-200 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500" 
               x-model="content(block, loc).heading" 
               @input="isDirty = true; queuePreviewUpdate()">
    </div>
    <div>
        <x-cms.field-header label="Subheading" field="subheading" :autoTranslate="true" />
        <textarea :id="'block-' + index + '-' + loc + '-subheading'"
                  :name="'blocks['+index+'][content]['+loc+'][subheading]'" 
                  x-model="content(block, loc).subheading" 
                  @input="isDirty = true; queuePreviewUpdate()" 
                  rows="3" 
                  class="w-full text-xs border border-slate-200 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500"></textarea>
    </div>
    <div class="grid grid-cols-2 gap-2">
        <div>
            <x-cms.field-header label="Button 1 Text (Primary)" field="cta_text" />
            <input type="text" 
                   :id="'block-' + index + '-' + loc + '-cta_text'"
                   :name="'blocks['+index+'][content]['+loc+'][cta_text]'" 
                   class="text-xs w-full border border-slate-200 rounded-lg p-2" 
                   x-model="content(block, loc).cta_text" 
                   @input="isDirty = true; queuePreviewUpdate()" 
                   placeholder="e.g. Innovation">
        </div>
        <div>
            <x-cms.field-header label="Button 1 URL" field="cta_url" />
            <input type="text" 
                   :id="'block-' + index + '-' + loc + '-cta_url'"
                   :name="'blocks['+index+'][content]['+loc+'][cta_url]'" 
                   class="text-xs w-full border border-slate-200 rounded-lg p-2" 
                   x-model="content(block, loc).cta_url" 
                   @input="isDirty = true; queuePreviewUpdate()" 
                   placeholder="/innovation">
        </div>
    </div>
    <div class="grid grid-cols-2 gap-2">
        <div>
            <x-cms.field-header label="Button 2 Text (Secondary)" field="cta2_text" />
            <input type="text" 
                   :id="'block-' + index + '-' + loc + '-cta2_text'"
                   :name="'blocks['+index+'][content]['+loc+'][cta2_text]'" 
                   class="text-xs w-full border border-slate-200 rounded-lg p-2" 
                   x-model="content(block, loc).cta2_text" 
                   @input="isDirty = true; queuePreviewUpdate()" 
                   placeholder="e.g. News">
        </div>
        <div>
            <x-cms.field-header label="Button 2 URL" field="cta2_url" />
            <input type="text" 
                   :id="'block-' + index + '-' + loc + '-cta2_url'"
                   :name="'blocks['+index+'][content]['+loc+'][cta2_url]'" 
                   class="text-xs w-full border border-slate-200 rounded-lg p-2" 
                   x-model="content(block, loc).cta2_url" 
                   @input="isDirty = true; queuePreviewUpdate()" 
                   placeholder="/news">
        </div>
    </div>
</div>
