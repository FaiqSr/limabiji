<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function index()
    {
        $files = collect(Storage::disk('public')->files('media'))
            ->map(fn ($path) => [
                'name' => basename($path),
                'url' => Storage::disk('public')->url($path),
                'size' => Storage::disk('public')->size($path),
                'modified' => Storage::disk('public')->lastModified($path),
            ])
            ->sortByDesc('modified')
            ->values();

        return view('admin.media.index', compact('files'));
    }

    public function upload(Request $request)
    {
        $validated = $request->validate([
            'file' => ['required', 'file', 'image', 'max:5120', 'mimes:jpg,jpeg,png,webp'],
            'context' => ['nullable', 'string', 'in:general,media,articles,origins'],
        ]);

        $context = $validated['context'] ?? 'media';
        $file = $request->file('file');

        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $filename = Str::slug($originalName).'-'.uniqid().'.'.$extension;

        $path = $file->storeAs("{$context}", $filename, 'public');

        return response()->json([
            'success' => true,
            'url' => '/storage/'.ltrim($path, '/'),
            'path' => $path,
            'name' => $filename,
        ]);
    }

    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'path' => ['required', 'string'],
        ]);

        if (Storage::disk('public')->exists($validated['path'])) {
            Storage::disk('public')->delete($validated['path']);

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'File not found.'], 404);
    }
}
