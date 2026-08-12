<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnalyticsEvent;
use App\Models\Article;
use App\Models\Origin;
use App\Models\Page;
use App\Models\Testimonial;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'pages_count' => Page::count(),
            'articles_count' => Article::count(),
            'origins_count' => Origin::count(),
            'testimonials_count' => Testimonial::count(),
            'pending_articles' => Article::where('status', 'pending')->count(),
            'page_views_7d' => AnalyticsEvent::pageViews(7),
            'unique_visitors_7d' => AnalyticsEvent::uniqueVisitors(7),
            'top_pages' => AnalyticsEvent::topPages(7, 5),
        ];

        $pendingArticlesList = Article::where('status', 'pending')->with('author')->latest()->take(5)->get();
        $origins = Origin::ordered()->get();
        $recentArticles = Article::with('author')->latest()->take(5)->get();

        return view('admin.dashboard.index', compact('stats', 'pendingArticlesList', 'origins', 'recentArticles'));
    }
}
