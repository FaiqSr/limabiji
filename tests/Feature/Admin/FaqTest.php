<?php

namespace Tests\Feature\Admin;

use App\Models\Faq;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FaqTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $editor;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->admin = User::where('role', 'admin')->first();
        $this->editor = User::factory()->create(['role' => 'editor']);
    }

    public function test_guest_cannot_access_faq_management(): void
    {
        $response = $this->get(route('admin.faqs.index'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_and_editor_can_view_faqs_index(): void
    {
        $responseAdmin = $this->actingAs($this->admin)->get(route('admin.faqs.index'));
        $responseAdmin->assertStatus(200);
        $responseAdmin->assertSee('FAQs Management');
        $responseAdmin->assertSee('How does your enzymatic civet coffee process work without animals?');

        $responseEditor = $this->actingAs($this->editor)->get(route('admin.faqs.index'));
        $responseEditor->assertStatus(200);
        $responseEditor->assertSee('FAQs Management');
    }

    public function test_can_search_and_filter_faqs(): void
    {
        $searchResponse = $this->actingAs($this->admin)->get(route('admin.faqs.index', ['search' => 'MOQ']));
        $searchResponse->assertStatus(200);
        $searchResponse->assertSee('Minimum Order Quantity');

        $filterResponse = $this->actingAs($this->admin)->get(route('admin.faqs.index', ['status' => 'active']));
        $filterResponse->assertStatus(200);
    }

    public function test_can_create_faq(): void
    {
        $payload = [
            'question' => 'What is the lead time for enzymatic green coffee shipping?',
            'question_id' => 'Berapa waktu tunggu untuk pengiriman kopi hijau enzimatik?',
            'answer' => 'Typically 7–14 days for air freight and 21–30 days for ocean freight.',
            'answer_id' => 'Biasanya 7–14 hari untuk kargo udara dan 21–30 hari untuk kargo laut.',
            'is_active' => '1',
            'order' => 6,
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.faqs.store'), $payload);

        $response->assertRedirect(route('admin.faqs.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('faqs', [
            'question' => 'What is the lead time for enzymatic green coffee shipping?',
            'question_id' => 'Berapa waktu tunggu untuk pengiriman kopi hijau enzimatik?',
            'order' => 6,
            'is_active' => true,
        ]);
    }

    public function test_can_update_faq(): void
    {
        $faq = Faq::first();

        $payload = [
            'question' => 'Updated FAQ Question EN',
            'question_id' => 'Pertanyaan FAQ ID yang Diperbarui',
            'answer' => 'Updated FAQ Answer EN',
            'answer_id' => 'Jawaban FAQ ID yang Diperbarui',
            'is_active' => '1',
            'order' => 10,
        ];

        $response = $this->actingAs($this->admin)->put(route('admin.faqs.update', $faq), $payload);

        $response->assertRedirect(route('admin.faqs.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('faqs', [
            'id' => $faq->id,
            'question' => 'Updated FAQ Question EN',
            'question_id' => 'Pertanyaan FAQ ID yang Diperbarui',
            'order' => 10,
        ]);
    }

    public function test_can_toggle_faq_active_status(): void
    {
        $faq = Faq::first();
        $this->assertTrue($faq->is_active);

        // Deactivate
        $response = $this->actingAs($this->admin)->post(route('admin.faqs.toggle-active', $faq));
        $response->assertRedirect();

        $faq->refresh();
        $this->assertFalse($faq->is_active);

        // Reactivate
        $response2 = $this->actingAs($this->admin)->post(route('admin.faqs.toggle-active', $faq));
        $response2->assertRedirect();

        $faq->refresh();
        $this->assertTrue($faq->is_active);
    }

    public function test_can_delete_faq(): void
    {
        $faq = Faq::first();

        $response = $this->actingAs($this->admin)->delete(route('admin.faqs.destroy', $faq));

        $response->assertRedirect(route('admin.faqs.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('faqs', [
            'id' => $faq->id,
        ]);
    }
}
