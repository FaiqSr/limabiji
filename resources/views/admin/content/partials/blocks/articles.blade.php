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
    <div class="space-y-2 rounded-lg border border-slate-200 bg-slate-50 p-3">
        <label class="flex items-center gap-2 cursor-pointer select-none">
            <input type="checkbox" 
                   :name="'blocks['+index+'][content]['+loc+'][show_all]'" 
                   value="1" 
                   :checked="Boolean(content(block, loc).show_all)" 
                   @change="toggleShowAll(block, $event.target.checked)" 
                   class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
            <span class="text-xs font-semibold text-slate-800">Tampilkan Semua Data (Tanpa Batas)</span>
        </label>
        <div>
            <label :for="'block-' + index + '-' + loc + '-limit'" class="text-xs font-semibold text-slate-700">Jumlah Tampil (Limit Items)</label>
            <input type="number" 
                   min="1" 
                   max="24" 
                   :id="'block-' + index + '-' + loc + '-limit'"
                   :name="'blocks['+index+'][content]['+loc+'][limit]'" 
                   class="text-xs w-full border border-slate-200 rounded-lg p-2 disabled:bg-slate-100 disabled:text-slate-400 disabled:cursor-not-allowed mt-1" 
                   :disabled="content(block, loc).show_all" 
                   :value="content(block, loc).show_all ? 0 : content(block, loc).limit" 
                   @input="setLimit(block, $event.target.value)" 
                   placeholder="6">
        </div>
    </div>
    <div class="p-3 bg-indigo-50 border border-indigo-100 rounded-lg text-xs text-indigo-700 space-y-1">
        <p class="font-bold flex items-center gap-1">
            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
            <span>Articles / News (Database)</span>
        </p>
        <p class="text-[11px] text-indigo-600 leading-relaxed">
            Daftar berita & artikel diambil secara otomatis dari Database. Anda dapat mengelola publikasi berita di menu 
            <a href="{{ route('admin.news.index') }}" target="_blank" class="font-bold underline text-indigo-800 hover:text-indigo-900">Admin > News</a>.
        </p>
    </div>
</div>
