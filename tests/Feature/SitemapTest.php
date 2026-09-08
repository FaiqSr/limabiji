<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Origin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SitemapTest extends TestCase
{
    use RefreshDatabase;

    public function test_sitemap_returns_valid_xml_response(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml; charset=utf-8');
        $this->assertStringContainsString('<?xml version="1.0" encoding="UTF-8"?>', $response->getContent());
        $this->assertStringContainsString('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"', $response->getContent());
        $this->assertStringContainsString(route('landingpages.home'), $response->getContent());
        $this->assertStringContainsString(route('landingpages.about'), $response->getContent());
        $this->assertStringContainsString(route('landingpages.innovation'), $response->getContent());
        $this->assertStringContainsString(route('landingpages.news'), $response->getContent());
        $this->assertStringContainsString(route('landingpages.contact'), $response->getContent());
        $this->assertStringContainsString(route('landingpages.privacy'), $response->getContent());
        $this->assertStringContainsString(route('landingpages.terms'), $response->getContent());
    }

    public function test_sitemap_includes_active_origins_and_published_articles(): void
    {
        $origin = Origin::create([
            'name' => 'Aceh Gayo Organic',
            'slug' => 'aceh-gayo-organic',
            'province' => 'Aceh',
            'altitude' => '1200-1500m',
            'varietals' => 'Bourbon, Catimor',
            'process' => 'Wet Hulled',
            'harvest' => 'Oct - May',
            'score' => '86.5',
            'overview' => 'Classic Aceh coffee',
            'is_active' => true,
            'order' => 1,
        ]);

        $author = User::factory()->create();

        $article = Article::create([
            'title' => 'Sustainable Coffee Harvesting 2026',
            'slug' => 'sustainable-coffee-harvesting-2026',
            'content' => '<p>Article content details</p>',
            'status' => 'published',
            'published_at' => now()->subDay(),
            'author_id' => $author->id,
        ]);

        // Draft article should not appear
        $draftArticle = Article::create([
            'title' => 'Unpublished Draft Article',
            'slug' => 'unpublished-draft-article',
            'content' => '<p>Draft content</p>',
            'status' => 'draft',
            'author_id' => $author->id,
        ]);

        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $this->assertStringContainsString(route('landingpages.origins', $origin->slug), $response->getContent());
        $this->assertStringContainsString(route('landingpages.news.detail', $article->slug), $response->getContent());
        $this->assertStringNotContainsString('unpublished-draft-article', $response->getContent());
    }
}
