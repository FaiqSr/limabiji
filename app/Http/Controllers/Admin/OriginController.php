<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Origin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class OriginController extends Controller
{
    public function index()
    {
        $origins = Origin::ordered()->get();

        return view('admin.origins.index', compact('origins'));
    }

    public function create()
    {
        return view('admin.origins.edit', ['origin' => new Origin]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('origins')],
            'province' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'string', 'max:2048'],
            'altitude' => ['required', 'string', 'max:255'],
            'varietals' => ['required', 'string', 'max:255'],
            'process' => ['required', 'string', 'max:255'],
            'harvest' => ['required', 'string', 'max:255'],
            'score' => ['required', 'string', 'max:10'],
            'overview' => ['required', 'string'],
            'overview_id' => ['nullable', 'string'],
            'flavor' => ['nullable', 'array'],
            'farms' => ['nullable', 'array'],
            'gallery' => ['nullable', 'array'],
            'gallery.*' => ['nullable', 'string', 'max:2048'],
            'is_active' => ['boolean'],
            'order' => ['integer', 'min:0'],
        ]);

        $validated['slug'] = Str::slug($validated['slug']);
        $validated['flavor'] = array_values(array_filter($validated['flavor'] ?? []));
        $validated['farms'] = array_values(array_filter($validated['farms'] ?? []));
        $validated['gallery'] = array_values(array_filter($validated['gallery'] ?? []));

        Origin::create($validated);

        return redirect()
            ->route('admin.origins.index')
            ->with('success', 'Origin created successfully.');
    }

    public function edit(Origin $origin)
    {
        return view('admin.origins.edit', compact('origin'));
    }

    public function update(Request $request, Origin $origin)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('origins')->ignore($origin->id)],
            'province' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'string', 'max:2048'],
            'altitude' => ['required', 'string', 'max:255'],
            'varietals' => ['required', 'string', 'max:255'],
            'process' => ['required', 'string', 'max:255'],
            'harvest' => ['required', 'string', 'max:255'],
            'score' => ['required', 'string', 'max:10'],
            'overview' => ['required', 'string'],
            'overview_id' => ['nullable', 'string'],
            'flavor' => ['nullable', 'array'],
            'flavor.*' => ['string'],
            'farms' => ['nullable', 'array'],
            'farms.*' => ['string'],
            'gallery' => ['nullable', 'array'],
            'gallery.*' => ['nullable', 'string', 'max:2048'],
            'is_active' => ['boolean'],
            'order' => ['integer', 'min:0'],
        ]);

        $validated['slug'] = Str::slug($validated['slug']);
        $validated['flavor'] = array_values(array_filter($validated['flavor'] ?? []));
        $validated['farms'] = array_values(array_filter($validated['farms'] ?? []));
        $newGallery = array_values(array_filter($validated['gallery'] ?? []));
        $validated['gallery'] = $newGallery;

        // Clean up replaced/removed featured image
        if ($origin->image && $origin->image !== ($validated['image'] ?? null)) {
            $this->cleanupStorageImages($origin->image);
        }

        // Clean up removed gallery images
        $oldGallery = $origin->gallery ?: [];
        $removedGalleryImages = array_diff($oldGallery, $newGallery);
        if (! empty($removedGalleryImages)) {
            $this->cleanupStorageImages($removedGalleryImages);
        }

        $origin->update($validated);

        return redirect()
            ->route('admin.origins.edit', $origin)
            ->with('success', 'Origin updated successfully.');
    }

    public function destroy(Origin $origin)
    {
        // Cleanup associated image files from storage
        if ($origin->image) {
            $this->cleanupStorageImages($origin->image);
        }

        if (! empty($origin->gallery)) {
            $this->cleanupStorageImages($origin->gallery);
        }

        $origin->delete();

        return redirect()
            ->route('admin.origins.index')
            ->with('success', 'Origin deleted.');
    }

    private function cleanupStorageImages(array|string|null $imageUrls): void
    {
        if (empty($imageUrls)) {
            return;
        }

        $urls = is_array($imageUrls) ? $imageUrls : [$imageUrls];

        foreach ($urls as $url) {
            if (! is_string($url) || trim($url) === '') {
                continue;
            }

            $path = null;
            if (str_contains($url, '/storage/')) {
                $path = Str::after($url, '/storage/');
            } elseif (! str_starts_with($url, 'http://') && ! str_starts_with($url, 'https://')) {
                $path = ltrim($url, '/');
            }

            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
    }
}
