@props([
    'data'   => [],
    'locale' => 'en',
])

@php
    $heading = $data['heading'] ?? '';
    $body    = $data['body']    ?? '';
@endphp

@if($heading || $body)
<div class="container mx-auto px-5 py-16">
    @if($heading)
        <x-section-heading :title="$heading" />
    @endif
    @if($body)
        <div class="prose prose-lg max-w-3xl text-dark/70 leading-relaxed">
            {!! nl2br(e($body)) !!}
        </div>
    @endif
</div>
@endif
