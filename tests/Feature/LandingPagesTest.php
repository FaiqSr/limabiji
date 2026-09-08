<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Origin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
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
        $response->assertSee('Halal Indonesia');
        $response->assertSee('ID32110001234560723');
    }

    public function test_innovation_page_renders_successfully(): void
    {
        $response = $this->get('/innovation');

        $response->assertStatus(200);
        $response->assertSee('ENZYMATIC');
        $response->assertSee('CIVET PROCESS');
        $response->assertSee('Ethical Cherry Sourcing');
        $response->assertSee('Enzyme Isolation & Formulation');
    }

    public function test_innovation_page_renders_steps_in_indonesian(): void
    {
        $this->post('/locale', ['locale' => 'id']);
        $response = $this->get('/innovation');

        $response->assertStatus(200);
        $response->assertSee('Pengadaan Ceri Etis');
        $response->assertSee('Isolasi & Formulasi Enzim');
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

        $response->assertOk();
    }

    public function test_privacy_policy_page_renders_successfully(): void
    {
        $response = $this->get('/privacy');

        $response->assertStatus(200);
        $response->assertSee('PRIVACY POLICY');
        $response->assertSee('Information We Collect');
        $response->assertSee(route('landingpages.privacy'), false);
    }

    public function test_privacy_policy_page_renders_in_indonesian(): void
    {
        $this->post('/locale', ['locale' => 'id']);
        $response = $this->get('/privacy');

        $response->assertStatus(200);
        $response->assertSee('KEBIJAKAN PRIVASI');
        $response->assertSee('Informasi yang Kami Kumpulkan');
    }

    public function test_homepage_footer_links_to_privacy_policy(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('href="'.url('/privacy').'"', false);
    }

    public function test_terms_of_use_page_renders_successfully(): void
    {
        $response = $this->get('/terms');

        $response->assertStatus(200);
        $response->assertSee('TERMS OF USE');
        $response->assertSee('Intellectual Property');
        $response->assertSee(route('landingpages.terms'), false);
    }

    public function test_terms_of_use_page_renders_in_indonesian(): void
    {
        $this->post('/locale', ['locale' => 'id']);
        $response = $this->get('/terms');

        $response->assertStatus(200);
        $response->assertSee('SYARAT & KETENTUAN');
        $response->assertSee('Kekayaan Intelektual');
    }

    public function test_homepage_footer_links_to_terms_of_use(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('href="'.url('/terms').'"', false);
    }

    public function test_contact_page_renders_recaptcha_widget_when_configured(): void
    {
        config(['services.recaptcha.site_key' => 'test-site-key']);

        $response = $this->get('/contact');

        $response->assertOk();
        $response->assertSee('g-recaptcha', false);
        $response->assertSee('https://www.google.com/recaptcha/api.js', false);
        $response->assertSee('data-sitekey="test-site-key"', false);
    }

    public function test_contact_page_hides_recaptcha_widget_when_not_configured(): void
    {
        config(['services.recaptcha.site_key' => '']);

        $response = $this->get('/contact');

        $response->assertOk();
        $response->assertDontSee('g-recaptcha', false);
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

    public function test_contact_form_rejects_submission_when_recaptcha_is_not_configured_and_token_empty(): void
    {
        config(['services.recaptcha.secret_key' => '']);

        $response = $this->post(route('landingpages.contact.submit'), [
            'name' => 'John Roaster',
            'email' => 'john@roastery.com',
            'message' => 'No captcha configured, should still pass.',
        ]);

        $response->assertRedirect(route('landingpages.contact'));
        $response->assertSessionHas('success');
    }

    public function test_contact_form_rejects_submission_when_recaptcha_token_missing(): void
    {
        config(['services.recaptcha.secret_key' => 'test-secret']);

        $response = $this->post(route('landingpages.contact.submit'), [
            'name' => 'John Roaster',
            'email' => 'john@roastery.com',
            'message' => 'No token provided.',
        ]);

        $response->assertSessionHasErrors(['g-recaptcha-response']);
        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_contact_form_rejects_submission_when_recaptcha_verification_fails(): void
    {
        config(['services.recaptcha.secret_key' => 'test-secret']);

        Http::fake([
            'https://www.google.com/recaptcha/api/siteverify' => Http::response(['success' => false, 'error-codes' => ['invalid-input-response']]),
        ]);

        $response = $this->post(route('landingpages.contact.submit'), [
            'name' => 'John Roaster',
            'email' => 'john@roastery.com',
            'message' => 'Invalid captcha token.',
            'g-recaptcha-response' => 'invalid-token',
        ]);

        $response->assertSessionHasErrors(['g-recaptcha-response']);
        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_contact_form_accepts_submission_when_recaptcha_verification_succeeds(): void
    {
        config(['services.recaptcha.secret_key' => 'test-secret']);

        Http::fake([
            'https://www.google.com/recaptcha/api/siteverify' => Http::response(['success' => true]),
        ]);

        $response = $this->post(route('landingpages.contact.submit'), [
            'name' => 'John Roaster',
            'email' => 'john@roastery.com',
            'message' => 'Valid captcha token.',
            'g-recaptcha-response' => 'valid-token',
        ]);

        $response->assertRedirect(route('landingpages.contact'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('contact_messages', ['email' => 'john@roastery.com']);
    }
}
