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
        <input type="text" 
               :id="'block-' + index + '-' + loc + '-subtitle'"
               :name="'blocks['+index+'][content]['+loc+'][subtitle]'" 
               class="text-xs w-full border border-slate-200 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500" 
               x-model="content(block, loc).subtitle" 
               @input="isDirty = true; queuePreviewUpdate()">
    </div>
    <div class="p-3 bg-indigo-50 border border-indigo-100 rounded-lg text-xs text-indigo-700 space-y-1">
        <p class="font-bold flex items-center gap-1">
            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 002 2h1.5a2.5 2.5 0 002.5-2.5V11a2 2 0 012-2h1.055M11 20.055V18a2 2 0 012-2h2a2 2 0 002-2v-1a2 2 0 012-2h2.945"/></svg>
            <span>Export Map Destinations (Database)</span>
        </p>
        <p class="text-[11px] text-indigo-600 leading-relaxed">
            Titik lokasi peta ekspor diambil secara otomatis dari Database. Anda dapat mengelola daftar negara & koordinat ekspor di menu 
            <a href="{{ route('admin.export-destinations.index') }}" target="_blank" class="font-bold underline text-indigo-800 hover:text-indigo-900">Admin > Export Destinations</a>.
        </p>
    </div>
</div>
