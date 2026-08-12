<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageVersion extends Model
{
    protected $fillable = [
        'page_id', 'user_id', 'version_number', 'content_snapshot',
    ];

    protected $casts = [
        'version_number' => 'integer',
        'content_snapshot' => 'array',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Create a snapshot of the current page blocks state.
     * Keeps max 5 versions per page.
     */
    public static function createSnapshot(Page $page, int $userId): self
    {
        $blocks = $page->blocks()->orderBy('order')->get()->toJson();

        $version = self::create([
            'page_id' => $page->id,
            'user_id' => $userId,
            'version_number' => self::getNextVersion($page->id),
            'content_snapshot' => json_decode($blocks, true),
        ]);

        // Keep only latest 5 versions
        $idsToKeep = self::where('page_id', $page->id)
            ->orderByDesc('version_number')
            ->take(5)
            ->pluck('id');

        self::where('page_id', $page->id)
            ->whereNotIn('id', $idsToKeep)
            ->delete();

        return $version;
    }

    private static function getNextVersion(int $pageId): int
    {
        $latest = self::where('page_id', $pageId)
            ->orderByDesc('version_number')
            ->first();

        return ($latest?->version_number ?? 0) + 1;
    }
}
