<?php

namespace Tests\Feature\Admin;

use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TestimonialTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->admin = User::where('role', 'admin')->first();
    }

    public function test_can_view_testimonials_index(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.testimonials.index'));

        $response->assertStatus(200);
        $response->assertSee('Hiroshi Tanaka');
        $response->assertSee('Sarah Chen');
        $response->assertDontSee('(5.0)');
    }

    public function test_can_store_testimonial_without_rating(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.testimonials.store'), [
            'name' => 'Elena Rostova',
            'company' => 'St. Petersburg Coffee Club',
            'content' => 'Exceptional coffee aroma and consistent lot deliveries.',
            'content_id' => 'Aroma kopi yang luar biasa dan pengiriman yang konsisten.',
            'is_featured' => true,
            'order' => 4,
        ]);

        $response->assertRedirect(route('admin.testimonials.index'));

        $this->assertDatabaseHas('testimonials', [
            'name' => 'Elena Rostova',
            'company' => 'St. Petersburg Coffee Club',
        ]);
    }

    public function test_can_update_testimonial(): void
    {
        $testimonial = Testimonial::first();

        $response = $this->actingAs($this->admin)->put(route('admin.testimonials.update', $testimonial), [
            'name' => 'Hiroshi Tanaka (Updated)',
            'company' => 'Tokyo Roastery Co.',
            'content' => 'Updated review quote content.',
            'content_id' => 'Konten kutipan ulasan yang diperbarui.',
            'is_featured' => false,
            'order' => 1,
        ]);

        $response->assertRedirect(route('admin.testimonials.edit', $testimonial));

        $this->assertDatabaseHas('testimonials', [
            'id' => $testimonial->id,
            'name' => 'Hiroshi Tanaka (Updated)',
        ]);
    }

    public function test_can_delete_testimonial(): void
    {
        $testimonial = Testimonial::first();

        $response = $this->actingAs($this->admin)->delete(route('admin.testimonials.destroy', $testimonial));

        $response->assertRedirect(route('admin.testimonials.index'));
        $this->assertDatabaseMissing('testimonials', [
            'id' => $testimonial->id,
        ]);
    }
}
