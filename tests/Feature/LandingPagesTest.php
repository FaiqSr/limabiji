<?php

namespace Tests\Feature;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LandingPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_homepage_renders_successfully_via_catch_all_route(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('LIMA BIJI AGRITECH');
    }

    public function test_dynamic_cms_pages_render_successfully(): void
    {
        $pages = ['innovation', 'testimonials', 'contact'];

        foreach ($pages as $slug) {
            $response = $this->get('/'.$slug);
            $response->assertStatus(200);
        }
    }

    public function test_news_page_renders_with_search_and_category_filter(): void
    {
        $response = $this->get('/news');
        $response->assertStatus(200);

        // Test search filter
        $searchResponse = $this->get('/news?q=Expansion');
        $searchResponse->assertStatus(200);
        $searchResponse->assertSee('Expansion to Japanese Market');

        // Test category filter
        $catResponse = $this->get('/news?category=Innovation');
        $catResponse->assertStatus(200);
    }

    public function test_non_existent_page_returns_404(): void
    {
        $response = $this->get('/non-existent-page-slug-123');
        $response->assertStatus(404);
    }

    public function test_article_detail_page_renders_successfully(): void
    {
        $article = Article::where('status', 'published')->first();
        if ($article) {
            $response = $this->get('/news/'.$article->slug);
            $response->assertStatus(200);
        }
    }
}
