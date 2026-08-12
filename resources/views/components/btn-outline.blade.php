@props(['href' => '#', 'label' => 'View More'])

<a href="{{ $href }}"
    class="inline-flex items-center justify-center gap-2 font-medium text-dark
           border border-dark/30 hover:border-dark hover:bg-surface-alt
           px-8 py-3 rounded-lg text-base transition-all duration-300">
    <span>{!! $label !!}</span>
</a>
