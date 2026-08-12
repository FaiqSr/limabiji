@props(['href' => '#', 'active' => false])
<a href="{{ $href }}"
   @class([
       'flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium transition-all duration-150',
       'bg-slate-900 text-white font-semibold shadow-2xs' => $active,
       'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80' => !$active,
   ])>
    {{ $slot }}
</a>