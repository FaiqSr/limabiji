<?php

namespace Tests\Feature\Admin;

use App\Models\Certificate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CertificateTest extends TestCase
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

    public function test_guest_cannot_access_certificate_management(): void
    {
        $response = $this->get(route('admin.certificates.index'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_and_editor_can_view_certificates_index(): void
    {
        $responseAdmin = $this->actingAs($this->admin)->get(route('admin.certificates.index'));
        $responseAdmin->assertStatus(200);
        $responseAdmin->assertSee('Certificates & Quality Standards');
        $responseAdmin->assertSee('Halal Indonesia');

        $responseEditor = $this->actingAs($this->editor)->get(route('admin.certificates.index'));
        $responseEditor->assertStatus(200);
        $responseEditor->assertSee('Certificates & Quality Standards');
    }

    public function test_can_search_and_filter_certificates(): void
    {
        $searchResponse = $this->actingAs($this->admin)->get(route('admin.certificates.index', ['search' => 'BPOM']));
        $searchResponse->assertStatus(200);
        $searchResponse->assertSee('BPOM RI');

        $filterResponse = $this->actingAs($this->admin)->get(route('admin.certificates.index', ['status' => 'active']));
        $filterResponse->assertStatus(200);
    }

    public function test_can_create_certificate(): void
    {
        $payload = [
            'name' => 'Rainforest Alliance Certified',
            'issuer' => 'Rainforest Alliance',
            'certificate_number' => 'RFA-ID-2024-0019',
            'logo' => 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?q=80&w=300&auto=format&fit=crop',
            'description' => 'Sustainable agriculture and biodiversity conservation certification.',
            'issued_date' => '2024-01-01',
            'expiry_date' => '2027-01-01',
            'is_active' => '1',
            'order' => 7,
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.certificates.store'), $payload);

        $response->assertRedirect(route('admin.certificates.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('certificates', [
            'name' => 'Rainforest Alliance Certified',
            'certificate_number' => 'RFA-ID-2024-0019',
            'order' => 7,
            'is_active' => true,
        ]);
    }

    public function test_can_update_certificate(): void
    {
        $cert = Certificate::first();

        $payload = [
            'name' => 'Updated Halal Certificate',
            'issuer' => 'BPJPH Kemenag',
            'certificate_number' => 'ID-UPDATED-99999',
            'logo' => $cert->logo,
            'description' => 'Updated description.',
            'is_active' => '1',
            'order' => 12,
        ];

        $response = $this->actingAs($this->admin)->put(route('admin.certificates.update', $cert), $payload);

        $response->assertRedirect(route('admin.certificates.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('certificates', [
            'id' => $cert->id,
            'name' => 'Updated Halal Certificate',
            'certificate_number' => 'ID-UPDATED-99999',
            'order' => 12,
        ]);
    }

    public function test_can_toggle_certificate_active_status(): void
    {
        $cert = Certificate::first();
        $this->assertTrue($cert->is_active);

        // Deactivate
        $response = $this->actingAs($this->admin)->post(route('admin.certificates.toggle-active', $cert));
        $response->assertRedirect();

        $cert->refresh();
        $this->assertFalse($cert->is_active);

        // Reactivate
        $response2 = $this->actingAs($this->admin)->post(route('admin.certificates.toggle-active', $cert));
        $response2->assertRedirect();

        $cert->refresh();
        $this->assertTrue($cert->is_active);
    }

    public function test_can_delete_certificate(): void
    {
        $cert = Certificate::first();

        $response = $this->actingAs($this->admin)->delete(route('admin.certificates.destroy', $cert));

        $response->assertRedirect(route('admin.certificates.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('certificates', [
            'id' => $cert->id,
        ]);
    }
}
