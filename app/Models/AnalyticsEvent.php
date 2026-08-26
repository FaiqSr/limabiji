<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnalyticsEvent extends Model
{
    protected $fillable = [
        'event_type', 'page', 'user_agent',
        'ip_address', 'referrer', 'session_id', 'properties',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    public $timestamps = false;

    /**
     * Record a new analytics event.
     */
    public static function record(
        string $eventType,
        ?string $page = null,
        ?string $referrer = null,
        ?string $sessionId = null,
        array $properties = []
    ): void {
        $request = request();

        self::create([
            'event_type' => $eventType,
            'page' => $page ?? $request->path(),
            'user_agent' => $request->userAgent(),
            'ip_address' => $request->ip(),
            'referrer' => $referrer ?? $request->header('referer'),
            'session_id' => $sessionId ?? session()->getId(),
            'properties' => $properties,
        ]);
    }

    /**
     * Get total page views for a given period.
     */
    public static function pageViews(int $days = 7): int
    {
        return self::where('event_type', 'page_view')
            ->where('created_at', '>=', now()->subDays($days))
            ->count();
    }

    /**
     * Get unique visitors for a given period.
     */
    public static function uniqueVisitors(int $days = 7): int
    {
        return self::where('event_type', 'page_view')
            ->where('created_at', '>=', now()->subDays($days))
            ->distinct('session_id')
            ->count('session_id');
    }

    /**
     * Get top pages for a given period.
     */
    public static function topPages(int $days = 7, int $limit = 10): array
    {
        return self::where('event_type', 'page_view')
            ->where('created_at', '>=', now()->subDays($days))
            ->selectRaw('page, COUNT(*) as views')
            ->groupBy('page')
            ->orderByDesc('views')
            ->limit($limit)
            ->get()
            ->toArray();
    }
}
