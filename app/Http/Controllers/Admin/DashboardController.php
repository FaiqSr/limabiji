<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnalyticsEvent;
use App\Models\Article;
use App\Models\ContactMessage;
use App\Models\ExportDestination;
use App\Models\Order;
use App\Models\Origin;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'export_destinations_count' => ExportDestination::count(),
            'articles_count' => Article::count(),
            'articles_published' => Article::where('status', 'published')->count(),
            'articles_draft' => Article::where('status', 'draft')->count(),
            'origins_count' => Origin::count(),
            'testimonials_count' => Testimonial::count(),
            'messages_count' => ContactMessage::count(),
            'messages_unread' => ContactMessage::unread()->count(),
            'page_views_7d' => AnalyticsEvent::pageViews(7),
            'unique_visitors_7d' => AnalyticsEvent::uniqueVisitors(7),
            'top_pages' => AnalyticsEvent::topPages(7, 5),
            // Store Metrics
            'orders_count' => Order::count(),
            'orders_paid_count' => Order::where('payment_status', 'paid')->count(),
            'orders_unfulfilled_count' => Order::where('payment_status', 'paid')->whereIn('shipping_status', ['unfulfilled', 'processing'])->count(),
            'store_revenue' => Order::where('payment_status', 'paid')->sum('total_amount'),
            'products_count' => Product::count(),
            'products_active_count' => Product::where('is_active', true)->count(),
            'products_low_stock_count' => Product::where('stock', '<=', 10)->count(),
        ];

        $origins = Origin::ordered()->get();
        $recentArticles = Article::with('author')->latest()->take(5)->get();
        $recentMessages = ContactMessage::latest()->take(5)->get();
        $recentOrders = Order::with('items')->latest()->take(5)->get();
        $siteSettings = SiteSetting::all()->groupBy('group');

        return view('admin.dashboard.index', compact('stats', 'origins', 'recentArticles', 'recentMessages', 'recentOrders', 'siteSettings'));
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
