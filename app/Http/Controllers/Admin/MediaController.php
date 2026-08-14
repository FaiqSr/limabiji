<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ImageOptimizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function index()
    {
        $allFiles = Storage::disk('public')->allFiles();

        $files = collect($allFiles)
            ->filter(fn ($path) => ! str_starts_with(basename($path), '.'))
            ->map(fn ($path) => [
                'name' => basename($path),
                'path' => $path,
                'url' => Storage::disk('public')->url($path),
                'size' => Storage::disk('public')->size($path),
                'modified' => Storage::disk('public')->lastModified($path),
            ])
            ->sortByDesc('modified')
            ->values();

        return view('admin.media.index', compact('files'));
    }

    public function upload(Request $request, ImageOptimizer $optimizer)
    {
        $validated = $request->validate([
            'file' => ['required', 'file', 'max:10240', 'mimes:jpg,jpeg,png,webp,svg,gif'],
            'context' => ['nullable', 'string', 'in:general,media,articles,origins'],
        ]);

        $context = $validated['context'] ?? 'media';
        $file = $request->file('file');

        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        $filename = Str::slug($originalName).'-'.uniqid().'.'.$extension;
        $targetPath = "{$context}/{$filename}";

        // Compress and store image
        $path = $optimizer->optimizeAndStore($file, $targetPath, 'public');
        $url = '/storage/'.ltrim($path, '/');

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'url' => $url,
                'path' => $path,
                'name' => $filename,
            ]);
        }

        return redirect()
            ->route('admin.media.index')
            ->with('success', 'File uploaded and compressed successfully.');
    }

    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'path' => ['required', 'string'],
        ]);

        $path = ltrim($validated['path'], '/');

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => true]);
            }

            return redirect()
                ->route('admin.media.index')
                ->with('success', 'File deleted successfully.');
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => false, 'message' => 'File not found.'], 404);
        }

        return redirect()
            ->route('admin.media.index')
            ->with('error', 'File not found.');
    }
}
