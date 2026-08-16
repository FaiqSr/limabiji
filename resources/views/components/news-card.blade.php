@props(['news'])
@php
    $isArray = is_array($news);
    $title = $isArray ? $news['title'] ?? '' : $news->getTitleForLocale(app()->getLocale());
    $image = $isArray ? $news['image'] ?? '' : $news->image;
    $excerpt = $isArray ? $news['excerpt'] ?? '' : $news->getExcerptForLocale(app()->getLocale());
    $slug = $isArray ? $news['slug'] ?? '' : $news->slug;

    $categoriesList = [];
    if (!$isArray && isset($news->categories) && $news->categories->isNotEmpty()) {
        $categoriesList = $news->categories->map(fn($c) => $c->getNameForLocale())->toArray();
    } elseif ($isArray && !empty($news['category'])) {
        $categoriesList = [$news['category']];
    } elseif (!$isArray && !empty($news->category)) {
        $categoriesList = [$news->category];
    }
@endphp

@if ($slug)
    <a href="{{ route('landingpages.news.detail', $slug) }}"
        class="group card-solid overflow-hidden flex flex-col hover:border-primary/40 transition-all duration-500 hover:-translate-y-2 relative">
    @else
        <div
            class="group card-solid overflow-hidden flex flex-col hover:border-primary/40 transition-all duration-500 hover:-translate-y-2">
@endif
<div class="relative h-80 overflow-hidden">
    <img src="{{ $image }}" alt="{{ $title }}"
        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
    <div class="absolute inset-0 bg-gradient-to-t from-card via-transparent to-transparent opacity-90"></div>
    @if (!empty($categoriesList))
        <div class="absolute top-4 left-4 flex flex-wrap gap-1.5 max-w-[80%]">
            @foreach ($categoriesList as $catLabel)
                <span class="bg-primary text-white text-xs font-bold px-3 py-1 rounded-lg shadow-sm">
                    {{ $catLabel }}
                </span>
            @endforeach
        </div>
    @endif
</div>
<div class="p-6 flex flex-col flex-1 absolute bottom-0">
    <div class="flex items-center gap-2 text-xs text-white mb-3 group-hover:translate-y-15 transition-transform duration-300">
        <span>{{ is_array($news) ? $news['date'] ?? '' : ($news->published_at ?? $news->created_at)->format('d M Y') }}</span>
    </div>
    <h3
        class="text-white font-bold text-lg group-hover:translate-y-15 transition-transform duration-300 line-clamp-2 leading-snug">
        {{ $title }}
    </h3>
    <p class="text-light-grey text-sm line-clamp-2 leading-relaxed mt-2 group-hover:opacity-0 transition-all">{{ $excerpt }}</p>
</div>
@if ($slug)
    </a>
@else
    </div>
@endif
