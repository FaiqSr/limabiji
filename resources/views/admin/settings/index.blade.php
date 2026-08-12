@extends('admin.layouts.app')
@section('title', 'Settings')
@section('page_title', 'Global Site Settings')

@section('content')
<form action="{{ route('admin.settings.update') }}" method="POST">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- General Settings Group Card -->
        <div class="card-modern space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-base font-semibold text-slate-900">General Configuration</h3>
                <span class="text-xs text-slate-400 font-mono">Group: General</span>
            </div>
            @php $settings = \App\Models\SiteSetting::all()->groupBy('group'); @endphp
            @forelse (($settings['general'] ?? collect()) as $setting)
            <div>
                <label class="text-xs font-semibold uppercase text-slate-600 tracking-wider">{{ str_replace('_', ' ', $setting->key) }}</label>
                <input type="text" name="settings[{{ $loop->index }}][value]" value="{{ is_array($setting->value) ? json_encode($setting->value) : $setting->value }}" class="mt-1">
                <input type="hidden" name="settings[{{ $loop->index }}][key]" value="{{ $setting->key }}">
                <input type="hidden" name="settings[{{ $loop->index }}][locale]" value="{{ $setting->locale }}">
                <input type="hidden" name="settings[{{ $loop->index }}][group]" value="general">
            </div>
            @empty
            <p class="text-xs text-slate-400 py-4">No general settings configured.</p>
            @endforelse
        </div>

        <!-- Contact Settings Group Card -->
        <div class="card-modern space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-base font-semibold text-slate-900">Contact & Support Details</h3>
                <span class="text-xs text-slate-400 font-mono">Group: Contact</span>
            </div>
            @forelse (($settings['contact'] ?? collect()) as $setting)
            <div>
                <div class="flex items-center justify-between">
                    <label class="text-xs font-semibold uppercase text-slate-600 tracking-wider">{{ str_replace('_', ' ', $setting->key) }}</label>
                    <span class="text-[10px] font-mono uppercase bg-slate-100 text-slate-500 px-1.5 py-0.5 rounded">{{ $setting->locale ?? 'global' }}</span>
                </div>
                <input type="text" name="settings[{{ $loop->index + 100 }}][value]" value="{{ is_array($setting->value) ? json_encode($setting->value) : $setting->value }}" class="mt-1">
                <input type="hidden" name="settings[{{ $loop->index + 100 }}][key]" value="{{ $setting->key }}">
                <input type="hidden" name="settings[{{ $loop->index + 100 }}][locale]" value="{{ $setting->locale }}">
                <input type="hidden" name="settings[{{ $loop->index + 100 }}][group]" value="contact">
            </div>
            @empty
            <p class="text-xs text-slate-400 py-4">No contact settings configured.</p>
            @endforelse
        </div>
    </div>

    <!-- Submit Floating Bar Card -->
    <div class="card-modern flex items-center justify-between">
        <p class="text-xs text-slate-500">Changes will reflect globally on public landing pages.</p>
        <button type="submit" class="btn btn-primary py-2.5 px-5 text-sm shadow-xs">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Save All Settings
        </button>
    </div>
</form>
@endsection