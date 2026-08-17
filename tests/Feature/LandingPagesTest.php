<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Origin;
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

    public function test_homepage_renders_successfully(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Lima Biji Agritech');
        $response->assertSee('SPECIALTY ENZYMATIC');
        $response->assertSee('How does your enzymatic civet coffee process work without animals?');
    }

    public function test_homepage_renders_faqs_in_indonesian(): void
    {
        $this->post('/locale', ['locale' => 'id']);
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Bagaimana proses kopi luwak enzimatik Anda bekerja tanpa hewan?');
    }

    public function test_about_page_renders_successfully(): void
    {
        $response = $this->get('/about');

        $response->assertStatus(200);
        $response->assertSee('ABOUT US');
        $response->assertSee('Cruelty-Free Ethics');
    }

    public function test_innovation_page_renders_successfully(): void
    {
        $response = $this->get('/innovation');

        $response->assertStatus(200);
        $response->assertSee('ENZYMATIC');
        $response->assertSee('CIVET PROCESS');
        $response->assertSee('Ethical Cherry Sourcing');
        $response->assertSee('Enzyme Isolation & Formulation', false);
    }

    public function test_innovation_page_renders_steps_in_indonesian(): void
    {
        $this->post('/locale', ['locale' => 'id']);
        $response = $this->get('/innovation');

        $response->assertStatus(200);
        $response->assertSee('Pengadaan Ceri Etis');
        $response->assertSee('Isolasi & Formulasi Enzim', false);
    }

    public function test_testimonials_page_renders_successfully(): void
    {
        $response = $this->get('/testimonials');

        $response->assertStatus(200);
        $response->assertSee('WHAT THEY');
    }

    public function test_contact_page_renders_successfully(): void
    {
        $response = $this->get('/contact');

        $response->assertStatus(200);
        $response->assertSee('export@limabijiagritech.com');
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

    public function test_origin_detail_page_renders_successfully(): void
    {
        $origin = Origin::active()->first();
        if ($origin) {
            $response = $this->get('/origin/'.$origin->slug);
            $response->assertStatus(200);
            $response->assertSee($origin->name);
        }
    }

    public function test_article_detail_page_renders_successfully(): void
    {
        $article = Article::where('status', 'published')->first();
        if ($article) {
            $response = $this->get('/news/'.$article->slug);
            $response->assertStatus(200);
            $response->assertSee($article->title);
        }
    }

    public function test_locale_switching_works(): void
    {
        $response = $this->post('/locale', ['locale' => 'id']);
        $response->assertRedirect();
        $response->assertSessionHas('locale', 'id');
    }

    public function test_contact_form_submission_creates_message_and_redirects(): void
    {
        $payload = [
            'name' => 'John Roaster',
            'email' => 'john@roastery.com',
            'company' => 'Craft Coffee Co.',
            'subject' => 'Green Bean Sample Request',
            'message' => 'We would love to sample your anaerobic civet beans.',
        ];

        $response = $this->post(route('landingpages.contact.submit'), $payload);

        $response->assertRedirect(route('landingpages.contact'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('contact_messages', [
            'name' => 'John Roaster',
            'email' => 'john@roastery.com',
            'company' => 'Craft Coffee Co.',
            'is_read' => false,
        ]);
    }

    public function test_contact_form_validation_fails_on_missing_required_fields(): void
    {
        $response = $this->post(route('landingpages.contact.submit'), [
            'company' => 'Incomplete Roastery',
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'message']);
    }
}
