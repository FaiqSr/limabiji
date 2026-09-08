<?php

namespace Tests\Feature\Admin;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->admin = User::where('role', 'admin')->first();
    }

    public function test_can_view_news_index(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.news.index'));

        $response->assertStatus(200);
        $response->assertSee('News & Articles Management');
    }

    public function test_can_search_news_articles(): void
    {
        Article::create([
            'title' => 'Innovative Enzymatic Processing in Java',
            'slug' => 'innovative-enzymatic-processing-java',
            'category' => 'Biotechnology',
            'excerpt' => 'Our breakthrough research in bio-fermentation.',
            'content' => '<p>Full content text...</p>',
            'status' => 'published',
            'author_id' => $this->admin->id,
            'published_at' => now(),
        ]);

        Article::create([
            'title' => 'Coffee Harvest Season in Sumatra',
            'slug' => 'coffee-harvest-season-sumatra',
            'category' => 'Origins',
            'excerpt' => 'Farmer partnerships in northern Sumatra.',
            'content' => '<p>Full content text...</p>',
            'status' => 'published',
            'author_id' => $this->admin->id,
            'published_at' => now(),
        ]);

        $searchResponse = $this->actingAs($this->admin)->get(route('admin.news.index', ['search' => 'Biotechnology']));

        $searchResponse->assertStatus(200);
        $searchResponse->assertSee('Innovative Enzymatic Processing in Java');
        $searchResponse->assertDontSee('Coffee Harvest Season in Sumatra');
    }

    public function test_news_pagination_and_query_string(): void
    {
        for ($i = 1; $i <= 15; $i++) {
            Article::create([
                'title' => "Batch Test Article #{$i}",
                'slug' => "batch-test-article-{$i}",
                'category' => 'Updates',
                'excerpt' => "Excerpt for article {$i}",
                'content' => "<p>Body content {$i}</p>",
                'status' => 'published',
                'author_id' => $this->admin->id,
                'published_at' => now(),
            ]);
        }

        $page1Response = $this->actingAs($this->admin)->get(route('admin.news.index'));
        $page1Response->assertStatus(200);
        $page1Response->assertSee('Showing');

        $page2Response = $this->actingAs($this->admin)->get(route('admin.news.index', ['page' => 2]));
        $page2Response->assertStatus(200);
    }

    public function test_can_create_article_with_multiple_categories(): void
    {
        $cat1 = Category::create(['name' => 'Category One', 'slug' => 'category-one']);
        $cat2 = Category::create(['name' => 'Category Two', 'slug' => 'category-two']);

        $response = $this->actingAs($this->admin)->post(route('admin.news.store'), [
            'title' => 'Article with Two Categories',
            'slug' => 'article-two-categories',
            'category_ids' => [$cat1->id, $cat2->id],
            'status' => 'published',
            'content' => '<p>Content here</p>',
        ]);

        $article = Article::where('slug', 'article-two-categories')->first();
        $this->assertNotNull($article);
        $this->assertCount(2, $article->categories);
        $this->assertTrue($article->categories->contains($cat1));
        $this->assertTrue($article->categories->contains($cat2));
    }

    public function test_can_update_article_categories(): void
    {
        $cat1 = Category::create(['name' => 'Original Cat', 'slug' => 'orig-cat']);
        $cat2 = Category::create(['name' => 'Replacement Cat', 'slug' => 'repl-cat']);

        $article = Article::create([
            'title' => 'Article to Update',
            'slug' => 'article-to-update',
            'status' => 'draft',
            'author_id' => $this->admin->id,
        ]);
        $article->categories()->attach($cat1->id);

        $response = $this->actingAs($this->admin)->put(route('admin.news.update', $article), [
            'title' => 'Article to Update Modified',
            'slug' => 'article-to-update',
            'category_ids' => [$cat2->id],
            'status' => 'published',
        ]);

        $article->refresh();
        $this->assertCount(1, $article->categories);
        $this->assertTrue($article->categories->contains($cat2));
        $this->assertFalse($article->categories->contains($cat1));
    }

    public function test_article_html_sanitizer_preserves_safe_rich_text(): void
    {
        $article = new Article([
            'content' => '<p>Hello <strong>World</strong> &amp; friends</p><ul><li>One</li></ul><a href="https://example.com" rel="noopener">Link</a>',
        ]);

        $html = $article->getHtmlContentForLocale('en');

        $this->assertStringContainsString('<p>Hello <strong>World</strong> &amp; friends</p>', $html);
        $this->assertStringContainsString('<ul><li>One</li></ul>', $html);
        $this->assertStringContainsString('<a href="https://example.com" rel="noopener">Link</a>', $html);
    }

    public function test_article_html_sanitizer_strips_scripts_and_event_handlers(): void
    {
        $article = new Article([
            'content' => '<p>Intro</p><script>alert(1)</script><p>Outro</p><img src="x.jpg" onerror="alert(2)"><a href="javascript:alert(3)">bad</a>',
        ]);

        $html = $article->getHtmlContentForLocale('en');

        $this->assertStringNotContainsString('<script', $html);
        $this->assertStringNotContainsString('alert(', $html);
        $this->assertStringNotContainsString('onerror', $html);
        $this->assertStringNotContainsString('javascript:', $html);
        $this->assertStringContainsString('<p>Intro</p>', $html);
        $this->assertStringContainsString('<p>Outro</p>', $html);
    }

    public function test_article_html_sanitizer_strips_disallowed_attributes(): void
    {
        $article = new Article([
            'content' => '<p style="color:red" class="x" onclick="steal()">Text</p><img src="data:image/png;base64,AAAA" width="10">',
        ]);

        $html = $article->getHtmlContentForLocale('en');

        $this->assertStringNotContainsString('style=', $html);
        $this->assertStringNotContainsString('class=', $html);
        $this->assertStringNotContainsString('onclick', $html);
        $this->assertStringContainsString('<p>Text</p>', $html);
        $this->assertStringContainsString('src="data:image/png;base64,AAAA"', $html);
    }
}
