@props([
    'data'   => [],
    'locale' => 'en',
])

@php
    $hidden  = $data['field_hidden'] ?? [];
    $heading = $data['heading'] ?? '';
    $body    = $data['body']    ?? '';
@endphp

@if((empty($hidden['heading']) && $heading) || (empty($hidden['body']) && $body))
<div class="container mx-auto px-5 py-16">
    @if(empty($hidden['heading']) && $heading)
        <x-section-heading :title="$heading" />
    @endif
    @if(empty($hidden['body']) && $body)
        <div class="prose prose-lg max-w-3xl text-dark/70 leading-relaxed">
            {!! nl2br(e($body)) !!}
        </div>
    @endif
</div>
@endif
