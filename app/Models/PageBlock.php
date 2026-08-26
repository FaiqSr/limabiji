<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageBlock extends Model
{
    protected $fillable = [
        'page_id', 'block_type', 'order', 'content', 'is_visible',
    ];

    protected $casts = [
        'content' => 'array',
        'is_visible' => 'boolean',
        'order' => 'integer',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function getContentAttribute($value)
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (is_string($decoded)) {
                $decoded = json_decode($decoded, true);
            }

            return is_array($decoded) ? $decoded : [];
        }

        return is_array($value) ? $value : [];
    }

    /**
     * Get sanitized HTML content for a specific field after rich text editing.
     */
    public function getHtmlContent(string $locale = 'en', string $field = 'body'): string
    {
        $content = $this->getContent($locale);
        $html = $content[$field] ?? '';

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

    /**
     * Get content for a specific locale safely as array.
     */
    public function getContent(string $locale = 'en'): array
    {
        $content = $this->content;

        if (is_string($content)) {
            $content = json_decode($content, true) ?: [];
        }

        if (! is_array($content)) {
            return [];
        }

        $localeContent = $content[$locale] ?? ($content['en'] ?? $content);

        if (is_string($localeContent)) {
            $localeContent = json_decode($localeContent, true) ?: [];
        }

        if (! is_array($localeContent)) {
            return [];
        }

        // Fallback 'limit' and 'show_all' from EN locale or top-level if not explicitly set for this locale
        if (! isset($localeContent['limit']) || $localeContent['limit'] === '' || $localeContent['limit'] === null) {
            $fallbackLimit = $content['en']['limit'] ?? ($content['id']['limit'] ?? ($content['limit'] ?? null));
            if ($fallbackLimit !== null && $fallbackLimit !== '') {
                $localeContent['limit'] = $fallbackLimit;
            }
        }

        if (! isset($localeContent['show_all'])) {
            $localeContent['show_all'] = $content['en']['show_all'] ?? ($content['id']['show_all'] ?? ($content['show_all'] ?? false));
        }

        // Auto-decode stringified 'items' array if stored as string from admin block editor
        if (isset($localeContent['items']) && is_string($localeContent['items'])) {
            $localeContent['items'] = json_decode($localeContent['items'], true) ?: [];
        }

        return $localeContent;
    }

    /**
     * Determine whether an individual field is marked as hidden for a given locale.
     */
    public function isFieldHidden(string $locale, string $field): bool
    {
        $content = $this->getContent($locale);
        $hidden = $content['field_hidden'] ?? [];

        if (is_string($hidden)) {
            $hidden = json_decode($hidden, true) ?: [];
        }

        if (! is_array($hidden)) {
            return false;
        }

        $val = $hidden[$field] ?? null;

        if ($val === null || $val === 0 || $val === '0' || $val === false || $val === 'false') {
            return false;
        }

        return true;
    }
}
