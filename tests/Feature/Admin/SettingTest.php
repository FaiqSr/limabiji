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

    public function test_contact_page_renders_updated_settings(): void
    {
        SiteSetting::set('contact_email', 'neworders@limabiji.com', null, 'contact');
        SiteSetting::set('contact_phone', '+62 877 1234 5678', null, 'contact');
        SiteSetting::set('contact_hours', 'Mon – Fri, 09:00 – 17:00 WIB', null, 'contact');
        SiteSetting::set('contact_address', 'Bandung Office, Indonesia', 'en', 'contact');

        $response = $this->get('/contact');
        $response->assertStatus(200);
        $response->assertSee('neworders@limabiji.com');
        $response->assertSee('+62 877 1234 5678');
        $response->assertSee('Mon – Fri, 09:00 – 17:00 WIB');
        $response->assertSee('Bandung Office, Indonesia');
    }

    public function test_settings_validation_requires_valid_email(): void
    {
        $response = $this->actingAs($this->admin)->put(route('admin.settings.update'), [
            'contact_email' => 'not-a-valid-email',
            'contact_phone' => '12345',
        ]);

        $response->assertSessionHasErrors(['contact_email']);
    }
}
