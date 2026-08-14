<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ExportDestination;
use App\Models\Origin;
use App\Models\Page;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class LandingPages extends Controller
{
    public function show(Request $request, ?string $slug = null)
    {
        $slug = $slug ?: 'home';

        $page = Page::where('slug', $slug)->with('blocks')->first();

        if (! $page) {
            abort(404);
        }

        $origins = Origin::active()->ordered()->get();
        $settings = SiteSetting::all()->keyBy('key');
        $exportDestinations = ExportDestination::active()->orderBy('order')->get();

        if ($slug === 'news' || $request->filled('q') || $request->filled('category')) {
            $articlesQuery = Article::with('categories')->where('status', 'published')->forLocale();

            if ($request->filled('q')) {
                $searchTerm = $request->q;
                $articlesQuery->where(function ($q) use ($searchTerm) {
                    $q->where('title', 'like', '%'.$searchTerm.'%')
                        ->orWhere('title_id', 'like', '%'.$searchTerm.'%');
                });
            }

            if ($request->filled('category') && $request->category !== 'All') {
                $categoryFilter = $request->category;
                $articlesQuery->where(function ($q) use ($categoryFilter) {
                    $q->whereHas('categories', function ($catQ) use ($categoryFilter) {
                        $catQ->where('slug', $categoryFilter)
                            ->orWhere('name', $categoryFilter)
                            ->orWhere('name_id', $categoryFilter);
                    })->orWhere('category', $categoryFilter);
                });
            }

            $articles = $articlesQuery->latest()->paginate(9)->withQueryString();
        } else {
            $articles = Article::with('categories')->where('status', 'published')->forLocale()->latest()->take(24)->get();
        }

        if ($slug === 'testimonials') {
            $testimonials = Testimonial::orderBy('order')->paginate(12)->withQueryString();
        } else {
            $testimonials = Testimonial::orderBy('order')->take(24)->get();
        }

        return view('landingpages.show', compact(
            'page',
            'origins',
            'articles',
            'testimonials',
            'settings',
            'exportDestinations',
            'slug'
        ));
    }

    public function index(Request $request)
    {
        return $this->show($request, 'home');
    }

    public function innovation(Request $request)
    {
        return $this->show($request, 'innovation');
    }

    public function news(Request $request)
    {
        return $this->show($request, 'news');
    }

    public function testimonials(Request $request)
    {
        return $this->show($request, 'testimonials');
    }

    public function contact(Request $request)
    {
        return $this->show($request, 'contact');
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

    public function newsDetail(Article $article)
    {
        if ($article->status !== 'published' || ($article->published_at && $article->published_at->isFuture())) {
            abort(404);
        }

        // Strict language content validation (must have both title and content for active locale)
        $locale = app()->getLocale();
        if ($locale === 'en' && (empty($article->title) || empty($article->content) || trim(strip_tags($article->content)) === '')) {
            abort(404);
        }
        if ($locale === 'id' && (empty($article->title_id) || empty($article->content_id) || trim(strip_tags($article->content_id)) === '')) {
            abort(404);
        }

        $article->increment('views');
        $article->load('categories');

        $categoryIds = $article->categories->pluck('id')->toArray();

        $related = Article::with('categories')
            ->where('status', 'published')
            ->forLocale()
            ->where('id', '!=', $article->id)
            ->where(function ($q) use ($categoryIds, $article) {
                if (! empty($categoryIds)) {
                    $q->whereHas('categories', function ($catQ) use ($categoryIds) {
                        $catQ->whereIn('categories.id', $categoryIds);
                    });
                }
                if (! empty($article->category)) {
                    $q->orWhere('category', $article->category);
                }
            })
            ->latest()
            ->take(3)
            ->get();

        return view('landingpages.news-detail', compact('article', 'related'));
    }
}
