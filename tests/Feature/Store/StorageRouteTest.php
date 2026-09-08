<?php

namespace Tests\Feature\Store;

use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StorageRouteTest extends TestCase
{
    public function test_valid_file_is_served_with_nosniff_header(): void
    {
        $fileName = 'storage-route-test-'.uniqid().'.txt';
        Storage::disk('public')->put($fileName, 'hello storage');

        try {
            $response = $this->get('/storage/'.$fileName);

            $response->assertStatus(200);
            $this->assertEquals('nosniff', $response->headers->get('X-Content-Type-Options'));
            $this->assertSame('hello storage', $response->streamedContent());
        } finally {
            Storage::disk('public')->delete($fileName);
        }
    }

    public function test_dotdot_path_traversal_is_rejected(): void
    {
        $this->get('/storage/../database/database.sqlite')->assertStatus(404);
        $this->get('/storage/foo/../../.env')->assertStatus(404);
    }

    public function test_encoded_dotdot_path_traversal_is_rejected(): void
    {
        $this->get('/storage/%2e%2e/database/database.sqlite')->assertStatus(404);
    }

    public function test_missing_file_returns_404(): void
    {
        $this->get('/storage/does-not-exist-'.uniqid().'.txt')->assertStatus(404);
    }

    public function test_directory_path_returns_404(): void
    {
        Storage::disk('public')->makeDirectory('storage-route-test-dir');

        try {
            $this->get('/storage/storage-route-test-dir')->assertStatus(404);
        } finally {
            Storage::disk('public')->deleteDirectory('storage-route-test-dir');
        }
    }
}
