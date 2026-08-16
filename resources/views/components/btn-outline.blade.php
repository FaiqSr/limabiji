@props(['href' => '#', 'label' => 'View More'])

<a href="{{ $href }}"
    class="inline-flex items-center justify-center gap-2 font-medium text-white
           border border-secondary hover:bg-secondary hover:text-white
           px-8 py-3 rounded-lg text-base transition-all duration-300">
    <span>{!! $label !!}</span>
</a>
