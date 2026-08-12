@props(['testimonial'])

<div class="card-solid p-6 sm:p-8 hover:border-primary/30 transition-all duration-300 hover:-translate-y-1">
    <p class="text-dark/70 text-sm leading-relaxed mb-6 italic">"{{ is_array($testimonial) ? ($testimonial['content'] ?? '') : $testimonial->content }}"</p>
    <div class="flex items-center gap-4 pt-4 border-t border-border">
        <div class="w-10 h-10 rounded-lg bg-primary/20 flex items-center justify-center text-primary font-bold text-lg">
            {{ strtoupper(substr(is_array($testimonial) ? ($testimonial['name'] ?? '') : $testimonial->name, 0, 1)) }}
        </div>
        <div>
            <p class="text-dark font-medium text-sm">{{ is_array($testimonial) ? ($testimonial['name'] ?? '') : $testimonial->name }}</p>
            @php($company = is_array($testimonial) ? ($testimonial['company'] ?? '') : $testimonial->company)
            @if ($company)
                <p class="text-xs text-dark/40">{{ $company }}</p>
            @endif
        </div>
    </div>
</div>