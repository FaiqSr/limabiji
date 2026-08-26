<?php

namespace App\Http\Middleware;

use App\Models\AnalyticsEvent;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TrackPageView
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Only track GET requests for pages (not assets, API, admin)
        if ($request->isMethod('GET')
            && ! $request->is('admin*')
            && ! $request->is('api*')
            && ! $request->is('build*')
            && ! $request->ajax()
            && ! $request->wantsJson()
        ) {
            try {
                AnalyticsEvent::record(
                    'page_view',
                    $request->path(),
                    $request->header('referer'),
                    session()->getId()
                );
            } catch (\Throwable $e) {
                // Silently fail — analytics should never break the page
                Log::warning('Analytics tracking failed: '.$e->getMessage());
            }
        }

        return $response;
    }
}
