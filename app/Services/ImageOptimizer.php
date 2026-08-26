<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageOptimizer
{
    /**
     * Maximum width or height for uploaded images (in pixels).
     */
    protected int $maxDimension = 2000;

    /**
     * Compression quality for JPEG/WebP (0-100).
     */
    protected int $quality = 82;

    /**
     * Compression level for PNG (0-9).
     */
    protected int $pngCompression = 8;

    /**
     * Optimize and store an uploaded image to the target storage disk.
     */
    public function optimizeAndStore(UploadedFile $file, string $targetPath, string $disk = 'public'): string
    {
        $contents = $this->optimize($file);
        Storage::disk($disk)->put($targetPath, $contents);

        return $targetPath;
    }

    /**
     * Compress / optimize the image file content.
     */
    public function optimize(UploadedFile|string $file): string
    {
        $filePath = $file instanceof UploadedFile ? $file->getRealPath() : $file;
        $mimeType = $file instanceof UploadedFile ? ($file->getMimeType() ?: '') : (mime_content_type($filePath) ?: '');
        $extension = strtolower($file instanceof UploadedFile ? $file->getClientOriginalExtension() : pathinfo($filePath, PATHINFO_EXTENSION));

        if (! file_exists($filePath) || filesize($filePath) === 0) {
            return $file instanceof UploadedFile ? (string) file_get_contents($file->getRealPath()) : '';
        }

        // SVG optimization (safe XML/whitespace minification)
        if ($mimeType === 'image/svg+xml' || $extension === 'svg') {
            return $this->optimizeSvg((string) file_get_contents($filePath));
        }

        // Check if GD extension is available
        if (! extension_loaded('gd')) {
            return (string) file_get_contents($filePath);
        }

        return $this->optimizeWithGd($filePath, $mimeType, $extension);
    }

    /**
     * Optimize bitmap image using GD.
     */
    protected function optimizeWithGd(string $filePath, string $mimeType, string $extension): string
    {
        $image = $this->createGdImage($filePath, $mimeType, $extension);

        if (! $image) {
            // Fallback to raw contents if GD cannot parse the file
            return (string) file_get_contents($filePath);
        }

        try {
            // Handle automatic proportional resizing if dimensions exceed $this->maxDimension
            $width = imagesx($image);
            $height = imagesy($image);

            if ($width > $this->maxDimension || $height > $this->maxDimension) {
                $image = $this->resizeGdImage($image, $width, $height);
            }

            // Export compressed binary buffer
            ob_start();

            if (in_array($extension, ['jpg', 'jpeg']) || str_contains($mimeType, 'jpeg')) {
                imagejpeg($image, null, $this->quality);
            } elseif ($extension === 'png' || str_contains($mimeType, 'png')) {
                imagealphablending($image, false);
                imagesavealpha($image, true);
                imagepng($image, null, $this->pngCompression);
            } elseif ($extension === 'webp' || str_contains($mimeType, 'webp')) {
                imagealphablending($image, false);
                imagesavealpha($image, true);
                if (function_exists('imagewebp')) {
                    imagewebp($image, null, $this->quality);
                } else {
                    imagejpeg($image, null, $this->quality);
                }
            } elseif ($extension === 'gif' || str_contains($mimeType, 'gif')) {
                imagegif($image);
            } else {
                imagejpeg($image, null, $this->quality);
            }

            $compressedData = ob_get_clean();

            imagedestroy($image);

            return $compressedData !== false ? $compressedData : (string) file_get_contents($filePath);
        } catch (\Throwable) {
            if (isset($image) && is_object($image)) {
                imagedestroy($image);
            }

            return (string) file_get_contents($filePath);
        }
    }

    /**
     * Create a GD image from file path based on type.
     */
    protected function createGdImage(string $filePath, string $mimeType, string $extension): mixed
    {
        try {
            if (in_array($extension, ['jpg', 'jpeg']) || str_contains($mimeType, 'jpeg')) {
                return @imagecreatefromjpeg($filePath);
            }
            if ($extension === 'png' || str_contains($mimeType, 'png')) {
                return @imagecreatefrompng($filePath);
            }
            if ($extension === 'webp' || str_contains($mimeType, 'webp')) {
                return function_exists('imagecreatefromwebp')
                    ? @imagecreatefromwebp($filePath)
                    : @imagecreatefromstring((string) file_get_contents($filePath));
            }
            if ($extension === 'gif' || str_contains($mimeType, 'gif')) {
                return @imagecreatefromgif($filePath);
            }

            return @imagecreatefromstring((string) file_get_contents($filePath));
        } catch (\Throwable) {
            return false;
        }
    }

    /**
     * Resize GD image proportionally.
     */
    protected function resizeGdImage(mixed $image, int $width, int $height): mixed
    {
        $ratio = min($this->maxDimension / $width, $this->maxDimension / $height);
        $newWidth = (int) round($width * $ratio);
        $newHeight = (int) round($height * $ratio);

        $resized = imagecreatetruecolor($newWidth, $newHeight);

        // Preserve alpha transparency for PNG/WebP/GIF
        imagealphablending($resized, false);
        imagesavealpha($resized, true);
        $transparent = imagecolorallocatealpha($resized, 255, 255, 255, 127);
        imagefilledrectangle($resized, 0, 0, $newWidth, $newHeight, $transparent);

        imagecopyresampled(
            $resized,
            $image,
            0,
            0,
            0,
            0,
            $newWidth,
            $newHeight,
            $width,
            $height
        );

        imagedestroy($image);

        return $resized;
    }

    /**
     * Safe SVG minification.
     */
    protected function optimizeSvg(string $svgContent): string
    {
        // Strip XML comments
        $clean = preg_replace('/<!--(.*?)-->/s', '', $svgContent);
        // Normalize multiple spaces/newlines
        $clean = preg_replace('/\s+/', ' ', (string) $clean);

        return trim((string) $clean);
    }
}
