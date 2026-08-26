<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Certificate;
use App\Models\ContactMessage;
use App\Models\ExportDestination;
use App\Models\Faq;
use App\Models\InnovationStep;
use App\Models\Origin;
use App\Models\Product;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class LandingPages extends Controller
{
    public function index()
    {
        $origins = Origin::active()->ordered()->get();
        $exportDestinations = ExportDestination::active()->orderBy('order')->get();
        $articles = Article::with('categories')
            ->where('status', 'published')
            ->forLocale()
            ->latest()
            ->take(3)
            ->get();
        $testimonials = Testimonial::ordered()->take(3)->get();
        $faqs = Faq::active()->ordered()->get();
        $featuredProducts = Product::active()->featured()->take(3)->get();

        return view('landingpages.index', compact(
            'origins',
            'exportDestinations',
            'articles',
            'testimonials',
            'faqs',
            'featuredProducts'
        ));
    }

    public function about()
    {
        $certificates = Certificate::active()->ordered()->get();

        return view('landingpages.about', compact('certificates'));
    }

    public function innovation()
    {
        $steps = InnovationStep::active()->ordered()->get();

        return view('landingpages.innovation', compact('steps'));
    }

    public function news(Request $request)
    {
        $categories = Category::orderBy('name')->get();
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

        return view('landingpages.news', compact('articles', 'categories'));
    }

    public function testimonials()
    {
        $testimonials = Testimonial::orderBy('order')->paginate(12)->withQueryString();

        return view('landingpages.testimonials', compact('testimonials'));
    }

    public function contact()
    {
        return view('landingpages.contact');
    }

    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'company' => ['nullable', 'string', 'max:255'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        ContactMessage::create($validated);

        return redirect()->route('landingpages.contact')->with('success', __('landing.contact_form_alert'));
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
