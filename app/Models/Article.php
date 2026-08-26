<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Article extends Model
{
    protected $fillable = [
        'title', 'title_id', 'slug', 'category', 'excerpt', 'excerpt_id', 'content', 'content_id',
        'image', 'author_id', 'status', 'published_at',
        'approved_by', 'approved_at', 'views',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'approved_at' => 'datetime',
        'views' => 'integer',
    ];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'article_category')
            ->withTimestamps();
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where('published_at', '<=', now());
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where(function ($q) use ($category) {
            $q->whereHas('categories', function ($catQ) use ($category) {
                $catQ->where('slug', $category)
                    ->orWhere('name', $category)
                    ->orWhere('name_id', $category);
            })->orWhere('category', $category);
        });
    }

    public function scopeForLocale($query, ?string $locale = null)
    {
        $locale = $locale ?: app()->getLocale();

        if ($locale === 'id') {
            return $query->whereNotNull('title_id')
                ->where('title_id', '!=', '')
                ->whereNotNull('content_id')
                ->where('content_id', '!=', '');
        }

        return $query->whereNotNull('title')
            ->where('title', '!=', '')
            ->whereNotNull('content')
            ->where('content', '!=', '');
    }

    public function getTitleForLocale(string $locale = 'en'): string
    {
        if ($locale === 'id' && ! empty($this->title_id)) {
            return $this->title_id;
        }

        return $this->title ?? '';
    }

    public function getContentForLocale(string $locale = 'en'): string
    {
        if ($locale === 'id' && $this->content_id) {
            return $this->content_id;
        }

        return $this->content ?? '';
    }

    /**
     * Get sanitized HTML content for display after rich text editing.
     */
    public function getHtmlContentForLocale(string $locale = 'en'): string
    {
        $html = $this->getContentForLocale($locale);

        if (empty($html)) {
            return '';
        }

        return strip_tags($html, [
            'p', 'br', 'strong', 'b', 'em', 'i', 'u', 's', 'del',
            'a', 'ul', 'ol', 'li', 'blockquote', 'pre', 'code',
            'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
            'img', 'span', 'div', 'hr',
        ]);
    }

    public function getExcerptForLocale(string $locale = 'en'): string
    {
        if ($locale === 'id') {
            if (! empty($this->excerpt_id)) {
                return $this->excerpt_id;
            }
            $content = $this->getContentForLocale('id');

            return Str::limit(strip_tags($content), 160);
        }

        return $this->excerpt ?? Str::limit(strip_tags($this->content ?? ''), 160);
    }
}
