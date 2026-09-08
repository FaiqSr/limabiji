<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ImageOptimizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
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
        $isJsonRequest = $request->expectsJson() || $request->wantsJson() || $request->ajax() || $request->header('Accept') === 'application/json' || $request->has('context');

        $validator = Validator::make($request->all(), [
            'file' => ['required', 'file', 'max:10240', 'mimes:jpg,jpeg,png,webp,gif'],
            'context' => ['nullable', 'string', 'in:general,media,articles,origins'],
        ]);

        if ($validator->fails()) {
            if ($isJsonRequest) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first('file') ?: 'The uploaded file is invalid or not supported.',
                    'errors' => $validator->errors(),
                ], 422);
            }

            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();
        $context = $validated['context'] ?? 'media';
        $file = $request->file('file');

        try {
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = strtolower($file->getClientOriginalExtension() ?: 'jpg');
            $filename = Str::slug($originalName).'-'.uniqid().'.'.$extension;
            $targetPath = "{$context}/{$filename}";

            // Compress and store image
            $path = $optimizer->optimizeAndStore($file, $targetPath, 'public');
            $url = '/storage/'.ltrim($path, '/');

            if ($isJsonRequest) {
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
        } catch (\Throwable $e) {
            if ($isJsonRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to process and store the image: '.$e->getMessage(),
                ], 500);
            }

            return redirect()->back()->with('error', 'Upload failed: '.$e->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'path' => ['required', 'string'],
        ]);

        $path = ltrim($validated['path'], '/');

        // Only ever delete files living in a known media folder with an
        // image extension, so a crafted path cannot escape storage.
        if (! preg_match('#^(articles|origins|certificates|media)/[a-zA-Z0-9_-]+\.(jpg|jpeg|png|webp|gif)$#', $path)) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Invalid file path.'], 422);
            }

            return redirect()
                ->route('admin.media.index')
                ->with('error', 'Invalid file path.');
        }

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
