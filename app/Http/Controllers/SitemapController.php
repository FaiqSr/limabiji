<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Origin;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $staticPages = [
            [
                'url' => route('landingpages.home'),
                'lastmod' => now()->startOfWeek()->toAtomString(),
                'changefreq' => 'daily',
                'priority' => '1.0',
            ],
            [
                'url' => route('landingpages.about'),
                'lastmod' => now()->startOfMonth()->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.8',
            ],
            [
                'url' => route('landingpages.innovation'),
                'lastmod' => now()->startOfMonth()->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.8',
            ],
            [
                'url' => route('landingpages.news'),
                'lastmod' => now()->startOfDay()->toAtomString(),
                'changefreq' => 'daily',
                'priority' => '0.9',
            ],
            [
                'url' => route('landingpages.testimonials'),
                'lastmod' => now()->startOfMonth()->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.6',
            ],
            [
                'url' => route('landingpages.contact'),
                'lastmod' => now()->startOfMonth()->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.7',
            ],
        ];

        $origins = Origin::active()
            ->ordered()
            ->get()
            ->map(function (Origin $origin) {
                return [
                    'url' => route('landingpages.origins', $origin->slug ?: strtolower($origin->name)),
                    'lastmod' => ($origin->updated_at ?? now())->toAtomString(),
                    'changefreq' => 'weekly',
                    'priority' => '0.8',
                    'image' => $origin->image ? url($origin->image) : null,
                    'title' => $origin->name,
                ];
            });

        $articles = Article::where('status', 'published')
            ->where(function ($query) {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->latest('updated_at')
            ->get()
            ->map(function (Article $article) {
                return [
                    'url' => route('landingpages.news.detail', $article->slug),
                    'lastmod' => ($article->updated_at ?? $article->published_at ?? now())->toAtomString(),
                    'changefreq' => 'weekly',
                    'priority' => '0.8',
                    'image' => $article->image ? url($article->image) : null,
                    'title' => $article->title ?: $article->title_id,
                ];
            });

        $xml = view('sitemap', [
            'staticPages' => $staticPages,
            'origins' => $origins,
            'articles' => $articles,
        ])->render();

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
        ]);
    }
}
