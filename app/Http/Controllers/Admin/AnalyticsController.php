<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnalyticsEvent;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $days = (int) $request->get('days', 30);
        $days = min($days, 365);

        $stats = [
            'total_views' => AnalyticsEvent::pageViews($days),
            'unique_visitors' => AnalyticsEvent::uniqueVisitors($days),
            'top_pages' => AnalyticsEvent::topPages($days, 10),
            'daily_views' => $this->dailyViews($days),
        ];

        return view('admin.analytics.index', compact('stats', 'days'));
    }

    private function dailyViews(int $days): array
    {
        return AnalyticsEvent::where('event_type', 'page_view')
            ->where('created_at', '>=', now()->subDays($days))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->toArray();
    }
}
