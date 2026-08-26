@props(['href' => '#', 'label' => 'Learn More', 'class' => ''])

<a href="{{ $href }}"
    class="inline-flex items-center justify-center gap-2 bg-primary hover:bg-primary-hover text-white
           px-8 py-3 rounded-lg font-medium text-base transition-all duration-300
           shadow-md hover:shadow-lg hover:-translate-y-0.5 {{ $class }}">
    <span>{!! $label !!}</span>
</a>
