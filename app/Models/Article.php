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

    private const RICH_TEXT_TAGS = [
        'p', 'br', 'strong', 'b', 'em', 'i', 'u', 's', 'del',
        'a', 'ul', 'ol', 'li', 'blockquote', 'pre', 'code',
        'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
        'img', 'span', 'div', 'hr',
    ];

    /**
     * Elements that carry executable content and are removed wholesale
     * (tag + content), rather than merely unwrapped.
     */
    private const UNSAFE_RICH_TEXT_TAGS = [
        'script', 'style', 'iframe', 'object', 'embed', 'applet',
        'frame', 'frameset', 'meta', 'link', 'base', 'form', 'template',
        'svg', 'math', 'noscript', 'noembed',
    ];

    private const RICH_TEXT_ATTRS = [
        'a' => ['href', 'target', 'rel', 'title'],
        'img' => ['src', 'alt', 'title', 'width', 'height'],
    ];

    /**
     * Get sanitized HTML content for display after rich text editing.
     *
     * Parses the stored HTML with DOMDocument and rebuilds it from an
     * allowlist: disallowed tags are removed (dangerous ones together with
     * their content), and every kept element is stripped of all attributes
     * except the explicit allowlist. Any href/src using a dangerous scheme is
     * removed. Malformed input falls back to escaped plain text.
     */
    public function getHtmlContentForLocale(string $locale = 'en'): string
    {
        $html = $this->getContentForLocale($locale);

        if (empty($html)) {
            return '';
        }

        try {
            return $this->sanitizeRichText((string) $html);
        } catch (\Throwable $e) {
            return htmlspecialchars((string) $html, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        }
    }

    protected function sanitizeRichText(string $html): string
    {
        $previous = libxml_use_internal_errors(true);
        $dom = new \DOMDocument('1.0', 'UTF-8');

        $loaded = $dom->loadHTML(
            '<?xml encoding="UTF-8">'.'<div id="lb-rich-text-root">'.$html.'</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );

        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        if (! $loaded) {
            return htmlspecialchars($html, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        }

        $xpath = new \DOMXPath($dom);
        $root = $xpath->query('//div[@id="lb-rich-text-root"]')->item(0);

        if (! $root instanceof \DOMElement) {
            return htmlspecialchars($html, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        }

        // Snapshot descendants so the tree can be mutated while iterating.
        $nodes = [];
        foreach ($xpath->query('.//*', $root) as $node) {
            if ($node instanceof \DOMElement) {
                $nodes[] = $node;
            }
        }

        // Walk deepest-first so unwrapped children are already sanitized.
        foreach (array_reverse($nodes) as $element) {
            if ($element->parentNode === null) {
                continue;
            }

            $tag = strtolower($element->tagName);

            if (! in_array($tag, self::RICH_TEXT_TAGS, true)) {
                if (in_array($tag, self::UNSAFE_RICH_TEXT_TAGS, true)) {
                    $element->parentNode->removeChild($element);
                } else {
                    $this->unwrapRichTextElement($element);
                }

                continue;
            }

            $this->stripRichTextAttributes($element, $tag);
        }

        // Comments can smuggle markup past the allowlist; drop them too.
        foreach ($xpath->query('.//comment()', $root) as $comment) {
            $comment->parentNode?->removeChild($comment);
        }

        $clean = '';
        foreach ($root->childNodes as $child) {
            $clean .= $dom->saveHTML($child);
        }

        return trim($clean);
    }

    /**
     * Move an element's children up into its parent (tag removal keeps text).
     */
    protected function unwrapRichTextElement(\DOMElement $element): void
    {
        $parent = $element->parentNode;
        if ($parent === null) {
            return;
        }

        while ($element->firstChild !== null) {
            $parent->insertBefore($element->firstChild, $element);
        }

        $parent->removeChild($element);
    }

    protected function stripRichTextAttributes(\DOMElement $element, string $tag): void
    {
        $allowed = self::RICH_TEXT_ATTRS[$tag] ?? [];

        foreach (iterator_to_array($element->attributes) as $attribute) {
            $name = strtolower((string) $attribute->nodeName);

            if (str_starts_with($name, 'on') || ! in_array($name, $allowed, true)) {
                $element->removeAttributeNode($attribute);

                continue;
            }

            if (($name === 'href' || $name === 'src') && ! $this->isSafeRichTextUrl((string) $attribute->nodeValue, $tag)) {
                $element->removeAttributeNode($attribute);
            }
        }
    }

    protected function isSafeRichTextUrl(string $value, string $tag): bool
    {
        $value = trim($value);

        if ($value === '' || str_starts_with(strtolower($value), '#')) {
            return true;
        }

        // Reject raw control whitespace that URL parsers strip before scheme detection.
        if (preg_match('/[\x00-\x20\x7F]/', $value)) {
            return false;
        }

        $scheme = strtolower((string) parse_url($value, PHP_URL_SCHEME));

        if ($scheme === '') {
            return true; // relative reference
        }

        if ($tag === 'img' && $scheme === 'data') {
            return str_starts_with(strtolower($value), 'data:image/');
        }

        return in_array($scheme, ['http', 'https', 'mailto', 'tel'], true);
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
