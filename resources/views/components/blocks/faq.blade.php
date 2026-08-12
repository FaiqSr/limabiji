@props([
    'data'   => [],
    'locale' => 'en',
])

@php
    $heading = $data['heading'] ?? '';
    $faqs    = $data['items']   ?? [];
    if (is_string($faqs)) {
        $faqs = json_decode($faqs, true) ?: [];
    }
    $subtitle = $locale === 'id'
        ? 'Semua yang perlu Anda ketahui tentang proses kopi luwak enzimatik bebas-kekejaman kami, sumber, dan pengiriman ekspor global.'
        : 'Everything you need to know about our cruelty-free enzymatic civet coffee process, sourcing, and global export shipping.';
@endphp

@if(!empty($faqs) && is_array($faqs) && count($faqs) > 0)
<div class="container mx-auto px-5 py-24">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
        <div>
            <x-section-heading title="{{ $heading }}" subtitle="{{ $subtitle }}" />
        </div>

        <div>
            @foreach ($faqs as $faq)
                <details data-animate="fade-up" class="faq-item group border-y border-border transition-colors duration-300 hover:border-primary/30 [&[open]]:border-primary/50 [&[open]]:bg-primary/5 overflow-hidden">
                    <summary class="flex justify-between items-center cursor-pointer p-6 sm:p-8 font-bold text-dark text-lg sm:text-xl select-none list-none gap-4">
                        <span>{{ $faq['question'] ?? '' }}</span>
                        <span class="faq-icon flex-shrink-0 w-10 h-10 rounded-lg bg-primary/10 border border-primary/20 group-hover:border-primary flex items-center justify-center text-primary transition-transform duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </span>
                    </summary>
                    <div class="faq-content grid grid-rows-[0fr] transition-[grid-template-rows] duration-300 ease-out">
                        <div class="overflow-hidden">
                            <div class="px-6 sm:px-8 pb-6 sm:pb-8 pt-0 text-dark/70 text-sm sm:text-base leading-relaxed border-t border-border mt-2 pt-4">
                                {!! $faq['answer'] ?? '' !!}
                            </div>
                        </div>
                    </div>
                </details>
            @endforeach
        </div>
    </div>
</div>

@once
@push('scripts')
<script>
    document.querySelectorAll('.faq-item').forEach((details) => {
        const summary = details.querySelector('summary');
        const content = details.querySelector('.faq-content');
        const icon = details.querySelector('.faq-icon');

        summary.addEventListener('click', (e) => {
            e.preventDefault();

            if (details.open) {
                content.classList.remove('grid-rows-[1fr]');
                content.classList.add('grid-rows-[0fr]');
                icon.classList.remove('rotate-180');

                setTimeout(() => {
                    details.open = false;
                }, 300);
            } else {
                details.open = true;
                icon.classList.add('rotate-180');

                requestAnimationFrame(() => {
                    content.classList.remove('grid-rows-[0fr]');
                    content.classList.add('grid-rows-[1fr]');
                });
            }
        });
    });
</script>
@endpush
@endonce
@endif
