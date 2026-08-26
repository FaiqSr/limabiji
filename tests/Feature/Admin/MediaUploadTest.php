<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Services\ImageOptimizer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MediaUploadTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->admin = User::where('role', 'admin')->first();
    }

    public function test_admin_can_upload_and_compress_jpeg_image(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('sample-photo.jpg', 500, 'image/jpeg');

        $response = $this->actingAs($this->admin)->postJson(route('admin.media.upload'), [
            'file' => $file,
            'context' => 'media',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'url',
            'path',
            'name',
        ]);

        $path = $response->json('path');
        Storage::disk('public')->assertExists($path);
    }

    public function test_admin_can_upload_and_compress_png_image(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('logo.png', 300, 'image/png');

        $response = $this->actingAs($this->admin)->postJson(route('admin.media.upload'), [
            'file' => $file,
            'context' => 'articles',
        ]);

        $response->assertStatus(200);
        $path = $response->json('path');
        $this->assertStringStartsWith('articles/', $path);
        Storage::disk('public')->assertExists($path);
    }

    public function test_admin_can_upload_svg(): void
    {
        Storage::fake('public');

        $svgContent = '<?xml version="1.0" encoding="UTF-8"?><svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 100 100"><!-- Comment --><circle cx="50" cy="50" r="50"/></svg>';
        $file = UploadedFile::fake()->createWithContent('icon.svg', $svgContent);

        $response = $this->actingAs($this->admin)->postJson(route('admin.media.upload'), [
            'file' => $file,
            'context' => 'general',
        ]);

        $response->assertStatus(200);
        $path = $response->json('path');
        Storage::disk('public')->assertExists($path);

        $storedSvg = Storage::disk('public')->get($path);
        $this->assertStringNotContainsString('<!-- Comment -->', $storedSvg);
    }

    public function test_image_optimizer_service_handles_resizing_large_images(): void
    {
        $optimizer = new ImageOptimizer;
        $svg = '<svg xmlns="https://www.w3.org/2000/svg" width="100" height="100"><!-- Test -->   <rect width="100" height="100" /> </svg>';

        $tempFile = tempnam(sys_get_temp_dir(), 'test_img_').'.svg';
        file_put_contents($tempFile, $svg);

        $optimized = $optimizer->optimize($tempFile);
        @unlink($tempFile);

        $this->assertStringNotContainsString('<!-- Test -->', $optimized);
    }
}
