@props(['title', 'subtitle' => null])

<div class="mb-12">
    <h2 class="section-heading">{!! $title !!}</h2>
    @if ($subtitle)
        <p class="section-subtitle mt-2">{!! $subtitle !!}</p>
    @endif
</div>