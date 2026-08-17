<?php

namespace Tests\Feature\Admin;

use App\Models\InnovationStep;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InnovationStepTest extends TestCase
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

    public function test_guest_cannot_access_innovation_steps(): void
    {
        $response = $this->get(route('admin.innovation-steps.index'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_and_editor_can_view_innovation_steps_index(): void
    {
        $responseAdmin = $this->actingAs($this->admin)->get(route('admin.innovation-steps.index'));
        $responseAdmin->assertStatus(200);
        $responseAdmin->assertSee('Enzymatic Innovation Steps');
        $responseAdmin->assertSee('Ethical Cherry Sourcing');

        $responseEditor = $this->actingAs($this->editor)->get(route('admin.innovation-steps.index'));
        $responseEditor->assertStatus(200);
        $responseEditor->assertSee('Enzymatic Innovation Steps');
    }

    public function test_can_search_and_filter_innovation_steps(): void
    {
        $searchResponse = $this->actingAs($this->admin)->get(route('admin.innovation-steps.index', ['search' => 'Fermentation']));
        $searchResponse->assertStatus(200);
        $searchResponse->assertSee('Controlled Fermentation');

        $filterResponse = $this->actingAs($this->admin)->get(route('admin.innovation-steps.index', ['status' => 'active']));
        $filterResponse->assertStatus(200);
    }

    public function test_can_create_innovation_step(): void
    {
        $payload = [
            'step_number' => '07',
            'title' => 'Cold Plasma Sterilization',
            'title_id' => 'Sterilisasi Plasma Dingin',
            'description' => 'Non-thermal treatment eliminating microbial load while preserving enzymatic flavor compounds.',
            'description_id' => 'Perlakuan non-termal untuk menghilangkan mikroba sambil mempertahankan senyawa rasa enzimatik.',
            'details' => 'Cold plasma tech, Zero heat damage, Microbial purity',
            'details_id' => 'Teknologi plasma dingin, Tanpa kerusakan panas, Kemurnian mikroba',
            'image' => 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd',
            'order' => 7,
            'is_active' => '1',
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.innovation-steps.store'), $payload);

        $response->assertRedirect(route('admin.innovation-steps.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('innovation_steps', [
            'title' => 'Cold Plasma Sterilization',
            'title_id' => 'Sterilisasi Plasma Dingin',
            'step_number' => '07',
            'order' => 7,
            'is_active' => true,
        ]);
    }

    public function test_can_update_innovation_step(): void
    {
        $step = InnovationStep::first();

        $payload = [
            'step_number' => '01',
            'title' => 'Updated Step Title EN',
            'title_id' => 'Judul Langkah Diperbarui ID',
            'description' => 'Updated description content text.',
            'description_id' => 'Teks deskripsi yang diperbarui.',
            'details' => 'Tag 1, Tag 2, Tag 3',
            'details_id' => 'Label 1, Label 2',
            'image' => 'https://images.unsplash.com/photo-1587734195503-904fca47e0e9',
            'order' => 1,
            'is_active' => '1',
        ];

        $response = $this->actingAs($this->admin)->put(route('admin.innovation-steps.update', $step), $payload);

        $response->assertRedirect(route('admin.innovation-steps.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('innovation_steps', [
            'id' => $step->id,
            'title' => 'Updated Step Title EN',
            'title_id' => 'Judul Langkah Diperbarui ID',
        ]);
    }

    public function test_can_toggle_innovation_step_active_status(): void
    {
        $step = InnovationStep::first();
        $this->assertTrue($step->is_active);

        // Deactivate
        $response = $this->actingAs($this->admin)->post(route('admin.innovation-steps.toggle-active', $step));
        $response->assertRedirect();

        $step->refresh();
        $this->assertFalse($step->is_active);

        // Reactivate
        $response2 = $this->actingAs($this->admin)->post(route('admin.innovation-steps.toggle-active', $step));
        $response2->assertRedirect();

        $step->refresh();
        $this->assertTrue($step->is_active);
    }

    public function test_can_delete_innovation_step(): void
    {
        $step = InnovationStep::first();

        $response = $this->actingAs($this->admin)->delete(route('admin.innovation-steps.destroy', $step));

        $response->assertRedirect(route('admin.innovation-steps.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('innovation_steps', [
            'id' => $step->id,
        ]);
    }
}
