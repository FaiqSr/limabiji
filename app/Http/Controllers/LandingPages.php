<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ExportDestination;
use App\Models\Origin;
use App\Models\Page;
use App\Models\SiteSetting;
use App\Models\Testimonial;

class LandingPages extends Controller
{
    public function index()
    {
        $homePage = Page::where('slug', 'home')->with('blocks')->first();
        $origins = Origin::active()->ordered()->get();
        $articles = Article::where('status', 'published')->forLocale()->latest()->take(3)->get();
        $testimonials = Testimonial::where('is_featured', true)->orderBy('order')->get();
        $settings = SiteSetting::all()->keyBy('key');
        $exportDestinations = ExportDestination::active()->orderBy('order')->get();

        return view('landingpages.index', compact('homePage', 'origins', 'articles', 'testimonials', 'settings', 'exportDestinations'));
    }

    public function origins(string $originName)
    {
        $origin = Origin::active()->where(function ($query) use ($originName) {
            $query->where('slug', strtolower($originName))
                ->orWhere('name', 'LIKE', $originName);
        })->first();

        if (! $origin) {
            abort(404);
        }

        return view('landingpages.origins', ['origin' => $origin]);
    }

    public function innovation()
    {
        $page = Page::where('slug', 'innovation')->with('blocks')->first();

        return view('landingpages.innovation', compact('page'));
    }

    public function news()
    {
        $page = Page::where('slug', 'news')->with('blocks')->first();
        $category = request()->get('category', 'All');
        $query = Article::where('status', 'published')->forLocale();

        if ($category !== 'All') {
            $query->where('category', $category);
        }

        $articles = $query->latest()->get();

        return view('landingpages.news', compact('page', 'articles', 'category'));
    }

    public function newsDetail(Article $article)
    {
        if ($article->status !== 'published' || ($article->published_at && $article->published_at->isFuture())) {
            abort(404);
        }

        // Strict language content validation
        $locale = app()->getLocale();
        if ($locale === 'en' && (empty($article->content) || trim(strip_tags($article->content)) === '')) {
            abort(404);
        }
        if ($locale === 'id' && (empty($article->content_id) || trim(strip_tags($article->content_id)) === '')) {
            abort(404);
        }

        $article->increment('views');

        $related = Article::where('status', 'published')
            ->forLocale()
            ->where('id', '!=', $article->id)
            ->where('category', $article->category)
            ->latest()
            ->take(3)
            ->get();

        return view('landingpages.news-detail', compact('article', 'related'));
    }

    public function testimonials()
    {
        $page = Page::where('slug', 'testimonials')->with('blocks')->first();
        $testimonials = Testimonial::orderBy('order')->get();

        return view('landingpages.testimonials', compact('page', 'testimonials'));
    }

    public function contact()
    {
        $page = Page::where('slug', 'contact')->with('blocks')->first();
        $settings = SiteSetting::all()->keyBy('key');

        return view('landingpages.contact', compact('page', 'settings'));
    }
}
