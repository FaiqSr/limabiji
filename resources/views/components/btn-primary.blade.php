@props(['href' => '#', 'label' => 'Learn More', 'class' => ''])

<a href="{{ $href }}"
    class="inline-flex items-center justify-center gap-2 bg-primary hover:bg-primary-hover text-white
           px-8 py-3 rounded-lg font-medium text-base transition-all duration-300
           shadow-md hover:shadow-lg hover:-translate-y-0.5 {{ $class }}">
    <span>{!! $label !!}</span>
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
    </svg>
</a>
