<?php

namespace Tests\Feature\Admin;

use App\Models\Origin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class OriginTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
        ]);
    }

    public function test_origins_index_page_is_accessible_and_displays_gallery_count(): void
    {
        Origin::create([
            'name' => 'Flores Bajawa',
            'slug' => 'flores-bajawa',
            'province' => 'NTT',
            'image' => '/storage/origins/flores.jpg',
            'altitude' => '1200-1500m',
            'varietals' => 'Yellow Caturra',
            'process' => 'Full Washed',
            'harvest' => 'June - Sept',
            'score' => '87+',
            'overview' => 'Flores coffee has chocolate notes.',
            'gallery' => ['/storage/origins/flores-1.jpg', '/storage/origins/flores-2.jpg'],
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.origins.index'));

        $response->assertStatus(200);
        $response->assertSee('Flores Bajawa');
        $response->assertSee('2 photos');
    }

    public function test_can_store_origin_with_uploaded_images(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('gayo.jpg');
        $uploadResponse = $this->actingAs($this->admin)->postJson(route('admin.media.upload'), [
            'file' => $file,
            'context' => 'origins',
        ]);

        $uploadResponse->assertStatus(200);
        $imageUrl = $uploadResponse->json('url');

        $storeResponse = $this->actingAs($this->admin)->post(route('admin.origins.store'), [
            'name' => 'Gayo Aceh',
            'slug' => 'gayo-aceh',
            'province' => 'Aceh',
            'image' => $imageUrl,
            'altitude' => '1200-1600m',
            'varietals' => 'Abyssinia',
            'process' => 'Semi-Washed',
            'harvest' => 'Sept - Dec',
            'score' => '86.5',
            'overview' => 'Rich body with spicy aroma.',
            'gallery' => [$imageUrl],
            'is_active' => 1,
            'order' => 0,
        ]);

        $storeResponse->assertRedirect(route('admin.origins.index'));
        $this->assertDatabaseHas('origins', [
            'name' => 'Gayo Aceh',
            'slug' => 'gayo-aceh',
            'image' => $imageUrl,
        ]);
    }

    public function test_updating_origin_cleans_up_removed_storage_files(): void
    {
        Storage::fake('public');

        $oldImageFile = UploadedFile::fake()->create('old-featured.jpg');
        $oldImagePath = $oldImageFile->storeAs('origins', 'old-featured.jpg', 'public');
        $oldImageUrl = Storage::disk('public')->url($oldImagePath);

        $oldGalleryFile = UploadedFile::fake()->create('old-gallery.jpg');
        $oldGalleryPath = $oldGalleryFile->storeAs('origins', 'old-gallery.jpg', 'public');
        $oldGalleryUrl = Storage::disk('public')->url($oldGalleryPath);

        $origin = Origin::create([
            'name' => 'Kintamani Bali',
            'slug' => 'kintamani-bali',
            'province' => 'Bali',
            'image' => $oldImageUrl,
            'altitude' => '1300-1700m',
            'varietals' => 'Bourbon',
            'process' => 'Natural',
            'harvest' => 'May - Oct',
            'score' => '88',
            'overview' => 'Citrusy notes.',
            'gallery' => [$oldGalleryUrl],
            'is_active' => true,
        ]);

        Storage::disk('public')->assertExists($oldImagePath);
        Storage::disk('public')->assertExists($oldGalleryPath);

        $newImageFile = UploadedFile::fake()->create('new-featured.jpg');
        $newImagePath = $newImageFile->storeAs('origins', 'new-featured.jpg', 'public');
        $newImageUrl = Storage::disk('public')->url($newImagePath);

        $response = $this->actingAs($this->admin)->put(route('admin.origins.update', $origin), [
            'name' => 'Kintamani Bali Updated',
            'slug' => 'kintamani-bali',
            'province' => 'Bali',
            'image' => $newImageUrl,
            'altitude' => '1300-1700m',
            'varietals' => 'Bourbon',
            'process' => 'Natural',
            'harvest' => 'May - Oct',
            'score' => '88',
            'overview' => 'Citrusy notes.',
            'gallery' => [],
            'is_active' => 1,
            'order' => 0,
        ]);

        $response->assertRedirect(route('admin.origins.edit', $origin));

        // Old files should be removed from storage
        Storage::disk('public')->assertMissing($oldImagePath);
        Storage::disk('public')->assertMissing($oldGalleryPath);
        // New file should remain
        Storage::disk('public')->assertExists($newImagePath);
    }

    public function test_deleting_origin_cleans_up_all_associated_storage_files(): void
    {
        Storage::fake('public');

        $imageFile = UploadedFile::fake()->create('toraja.jpg');
        $imagePath = $imageFile->storeAs('origins', 'toraja.jpg', 'public');
        $imageUrl = Storage::disk('public')->url($imagePath);

        $galleryFile = UploadedFile::fake()->create('toraja-farm.jpg');
        $galleryPath = $galleryFile->storeAs('origins', 'toraja-farm.jpg', 'public');
        $galleryUrl = Storage::disk('public')->url($galleryPath);

        $origin = Origin::create([
            'name' => 'Toraja Sapan',
            'slug' => 'toraja-sapan',
            'province' => 'South Sulawesi',
            'image' => $imageUrl,
            'altitude' => '1400-1900m',
            'varietals' => 'S795',
            'process' => 'Washed',
            'harvest' => 'May - Nov',
            'score' => '89',
            'overview' => 'Herbal and chocolate notes.',
            'gallery' => [$galleryUrl],
            'is_active' => true,
        ]);

        Storage::disk('public')->assertExists($imagePath);
        Storage::disk('public')->assertExists($galleryPath);

        $response = $this->actingAs($this->admin)->delete(route('admin.origins.destroy', $origin));

        $response->assertRedirect(route('admin.origins.index'));
        $this->assertDatabaseMissing('origins', ['id' => $origin->id]);

        Storage::disk('public')->assertMissing($imagePath);
        Storage::disk('public')->assertMissing($galleryPath);
    }

    public function test_can_render_edit_page_for_origin_with_string_or_json_flavor_attributes(): void
    {
        $origin = Origin::create([
            'name' => 'Legacy Origin',
            'slug' => 'legacy-origin',
            'province' => 'Java',
            'altitude' => '1000m',
            'varietals' => 'Typica',
            'process' => 'Washed',
            'harvest' => 'Jan - Mar',
            'score' => '80',
            'overview' => 'Test overview',
            'flavor' => '["Chocolate", "Nutty"]',
            'farms' => 'Farm A, Farm B',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.origins.edit', $origin));

        $response->assertStatus(200);
        $response->assertSee('Legacy Origin');
        $response->assertSee('Chocolate');
        $response->assertSee('Farm A');
    }
}
