<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnalyticsEvent;
use App\Models\Article;
use App\Models\Origin;
use App\Models\Page;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'pages_count' => Page::count(),
            'articles_count' => Article::count(),
            'articles_published' => Article::where('status', 'published')->count(),
            'articles_draft' => Article::where('status', 'draft')->count(),
            'origins_count' => Origin::count(),
            'testimonials_count' => Testimonial::count(),
            'page_views_7d' => AnalyticsEvent::pageViews(7),
            'unique_visitors_7d' => AnalyticsEvent::uniqueVisitors(7),
            'top_pages' => AnalyticsEvent::topPages(7, 5),
        ];

        $origins = Origin::ordered()->get();
        $recentArticles = Article::with('author')->latest()->take(5)->get();
        $siteSettings = SiteSetting::all()->groupBy('group');

        return view('admin.dashboard.index', compact('stats', 'origins', 'recentArticles', 'siteSettings'));
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'settings' => ['required', 'array'],
            'settings.*.key' => ['required', 'string', 'max:255'],
            'settings.*.value' => ['required'],
            'settings.*.locale' => ['nullable', 'string', 'in:en,id'],
            'settings.*.group' => ['required', 'string', 'max:100'],
        ]);

        foreach ($validated['settings'] as $setting) {
            SiteSetting::set(
                $setting['key'],
                $setting['value'],
                $setting['locale'] ?? null,
                $setting['group']
            );
        }

        return redirect()->route('admin.dashboard')->with('success', 'Global site settings saved successfully.');
    }
}
