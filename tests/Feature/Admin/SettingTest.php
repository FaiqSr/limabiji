<?php

namespace Tests\Feature\Admin;

use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingTest extends TestCase
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

    public function test_guest_cannot_access_settings(): void
    {
        $response = $this->get(route('admin.settings.index'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_and_editor_can_view_settings_page(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.settings.index'));
        $response->assertStatus(200);
        $response->assertSee('Website & Contact Configurations', false);
        $response->assertSee('export@limabijiagritech.com');
        $response->assertSee('+62 812 3456 7890');

        $responseEditor = $this->actingAs($this->editor)->get(route('admin.settings.index'));
        $responseEditor->assertStatus(200);
        $responseEditor->assertSee('Website & Contact Configurations', false);
    }

    public function test_can_update_contact_settings(): void
    {
        $payload = [
            'contact_email' => 'sales@customlimabiji.com',
            'contact_phone' => '+62 811 9999 8888',
            'contact_hours' => 'Mon – Sat, 07:00 – 17:00 WIB',
            'contact_address_en' => 'Bandung Highlands, West Java, Indonesia',
            'contact_address_id' => 'Dataran Tinggi Bandung, Jawa Barat, Indonesia',
            'site_name_en' => 'Lima Biji Specialty',
            'site_name_id' => 'Lima Biji Specialty',
        ];

        $response = $this->actingAs($this->admin)->put(route('admin.settings.update'), $payload);

        $response->assertRedirect(route('admin.settings.index'));
        $response->assertSessionHas('success');

        $this->assertEquals('sales@customlimabiji.com', SiteSetting::get('contact_email'));
        $this->assertEquals('+62 811 9999 8888', SiteSetting::get('contact_phone'));
        $this->assertEquals('Bandung Highlands, West Java, Indonesia', SiteSetting::get('contact_address', 'en'));
        $this->assertEquals('Dataran Tinggi Bandung, Jawa Barat, Indonesia', SiteSetting::get('contact_address', 'id'));
    }

    public function test_can_update_map_settings(): void
    {
        $payload = [
            'contact_email' => 'sales@customlimabiji.com',
            'contact_phone' => '+62 811 9999 8888',
            'map_lat' => -6.9175,
            'map_lng' => 107.6191,
            'map_zoom' => 16,
            'map_label' => 'Lima Biji Bandung Roastery',
            'map_embed_url' => 'https://maps.google.com/maps?q=-6.9175,107.6191&z=16&output=embed',
        ];

        $response = $this->actingAs($this->admin)->put(route('admin.settings.update'), $payload);

        $response->assertRedirect(route('admin.settings.index'));
        $response->assertSessionHas('success');

        $this->assertEquals(-6.9175, SiteSetting::get('map_lat'));
        $this->assertEquals(107.6191, SiteSetting::get('map_lng'));
        $this->assertEquals(16, SiteSetting::get('map_zoom'));
        $this->assertEquals('Lima Biji Bandung Roastery', SiteSetting::get('map_label'));
        $this->assertEquals('https://maps.google.com/maps?q=-6.9175,107.6191&z=16&output=embed', SiteSetting::get('map_embed_url'));
    }

    public function test_contact_page_renders_updated_settings(): void
    {
        SiteSetting::set('contact_email', 'neworders@limabiji.com', null, 'contact');
        SiteSetting::set('contact_phone', '+62 877 1234 5678', null, 'contact');
        SiteSetting::set('contact_hours', 'Mon – Fri, 09:00 – 17:00 WIB', null, 'contact');
        SiteSetting::set('contact_address', 'Bandung Office, Indonesia', 'en', 'contact');
        SiteSetting::set('map_lat', -6.9175, null, 'map');
        SiteSetting::set('map_lng', 107.6191, null, 'map');
        SiteSetting::set('map_label', 'Lima Biji Bandung Hub', null, 'map');

        $response = $this->get('/contact');
        $response->assertStatus(200);
        $response->assertSee('neworders@limabiji.com');
        $response->assertSee('+62 877 1234 5678');
        $response->assertSee('Mon – Fri, 09:00 – 17:00 WIB');
        $response->assertSee('Bandung Office, Indonesia');
        $response->assertSee('-6.9175');
        $response->assertSee('107.6191');
        $response->assertSee('Lima Biji Bandung Hub');
        $response->assertSee('contact-map-iframe');
    }

    public function test_settings_validation_requires_valid_email(): void
    {
        $response = $this->actingAs($this->admin)->put(route('admin.settings.update'), [
            'contact_email' => 'not-a-valid-email',
            'contact_phone' => '12345',
        ]);

        $response->assertSessionHasErrors(['contact_email']);
    }

    public function test_map_coordinates_validation(): void
    {
        $response = $this->actingAs($this->admin)->put(route('admin.settings.update'), [
            'contact_email' => 'admin@limabiji.com',
            'contact_phone' => '+62812345678',
            'map_lat' => 120.5, // Invalid latitude (> 90)
            'map_lng' => -200.0, // Invalid longitude (< -180)
            'map_zoom' => 25, // Invalid zoom (> 20)
        ]);

        $response->assertSessionHasErrors(['map_lat', 'map_lng', 'map_zoom']);
    }

    public function test_can_update_separate_stat_settings(): void
    {
        $payload = [
            'contact_email' => 'admin@limabiji.com',
            'contact_phone' => '+62812345678',
            'stat_about_sca_score' => '86+',
            'stat_innovation_sca_score' => '84+',
            'stat_export_destinations' => '12+',
        ];

        $response = $this->actingAs($this->admin)->put(route('admin.settings.update'), $payload);

        $response->assertRedirect(route('admin.settings.index'));
        $response->assertSessionHas('success');

        $this->assertEquals('86+', SiteSetting::get('stat_about_sca_score'));
        $this->assertEquals('84+', SiteSetting::get('stat_innovation_sca_score'));
        $this->assertEquals('12+', SiteSetting::get('stat_export_destinations'));
    }

    public function test_separate_stat_settings_render_on_about_and_innovation_pages(): void
    {
        SiteSetting::set('stat_about_sca_score', '88+', null, 'stats');
        SiteSetting::set('stat_innovation_sca_score', '83+', null, 'stats');
        SiteSetting::set('stat_export_destinations', '15+', null, 'stats');

        $aboutResponse = $this->get('/about');
        $aboutResponse->assertStatus(200);
        $aboutResponse->assertSee('88+ SCA');
        $aboutResponse->assertDontSee('83+ SCA');

        $innovationResponse = $this->get('/innovation');
        $innovationResponse->assertStatus(200);
        $innovationResponse->assertSee('83+');
        $innovationResponse->assertSee('15+');
        $innovationResponse->assertDontSee('88+');
    }
}
