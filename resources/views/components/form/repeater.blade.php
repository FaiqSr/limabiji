@props([
    'name',
    'items' => [],
    'placeholder' => 'Enter item...',
    'buttonLabel' => '+ Add Item',
    'label' => null,
])

@php
    $initialItems = [];
    $rawItems = old($name, $items);
    if (is_array($rawItems)) {
        foreach ($rawItems as $val) {
            if (is_string($val) && trim($val) !== '') {
                $initialItems[] = $val;
            }
        }
    }
@endphp

<div class="card-modern space-y-3"
     x-data="{
        list: ({{ \Illuminate\Support\Js::from($initialItems) }} || []).map(val => ({
            id: 'item-' + Date.now() + '-' + Math.random().toString(36).substring(2, 9),
            val: String(val || '')
        })),
        addItem() {
            this.list.push({
                id: 'item-' + Date.now() + '-' + Math.random().toString(36).substring(2, 9),
                val: ''
            });
            this.$nextTick(() => {
                const inputs = this.$el.querySelectorAll('input[type=\'text\']');
                if (inputs.length > 0) {
                    inputs[inputs.length - 1].focus();
                }
            });
        },
        removeItem(index) {
            this.list.splice(index, 1);
        }
     }">
    @if ($label)
        <div class="flex items-center justify-between pb-2 border-b border-slate-100">
            <h3 class="text-base font-semibold text-slate-900">{{ $label }}</h3>
            <span class="text-xs text-slate-400 font-mono" x-text="list.length + ' {{ Str::plural('item', 2) }}'"></span>
        </div>
    @endif

    <div class="space-y-2">
        <template x-for="(item, index) in list" :key="item.id">
            <div x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 -translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-1"
                 class="flex items-center gap-2">
                
                {{-- Strict HTML name array format: name[] ensures clean 0-indexed PHP array --}}
                <input type="text" 
                       :name="'{{ $name }}[]'" 
                       x-model="item.val" 
                       placeholder="{{ $placeholder }}" 
                       class="w-full text-xs bg-white border border-slate-200 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500">
                
                <button type="button" 
                        @click="removeItem(index)" 
                        class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg border border-transparent hover:border-rose-200 transition-colors shrink-0"
                        title="Remove item"
                        aria-label="Remove item">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </template>

        <div x-show="list.length === 0" class="py-4 text-center text-xs text-slate-400 bg-slate-50 rounded-lg border border-dashed border-slate-200">
            No items added yet. Click the button below to add.
        </div>
    </div>

    <button type="button" 
            @click="addItem()" 
            class="btn btn-secondary py-1.5 px-3 text-xs font-semibold mt-1 inline-flex items-center gap-1.5">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        <span>{{ $buttonLabel }}</span>
    </button>

    @error($name)
        <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
    @enderror
    @error($name . '.*')
        <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
    @enderror
</div>
