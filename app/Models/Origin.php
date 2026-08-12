<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Origin extends Model
{
    protected $fillable = [
        'name', 'slug', 'province', 'image', 'altitude',
        'varietals', 'process', 'harvest', 'score',
        'overview', 'overview_id', 'flavor', 'farms', 'gallery',
        'is_active', 'order',
    ];

    protected $casts = [
        'flavor' => 'array',
        'farms' => 'array',
        'gallery' => 'array',
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public function getOverviewForLocale(string $locale = 'en'): string
    {
        if ($locale === 'id' && $this->overview_id) {
            return $this->overview_id;
        }

        return $this->overview;
    }

    public function getImageAttribute(?string $value): ?string
    {
        if (! $value) {
            return null;
        }

        if (str_contains($value, '/storage/')) {
            return '/storage/'.ltrim(Str::after($value, '/storage/'), '/');
        }

        return $value;
    }

    public function getFlavorAttribute($value): array
    {
        return $this->parseJsonOrArrayAttribute($value);
    }

    public function getFarmsAttribute($value): array
    {
        return $this->parseJsonOrArrayAttribute($value);
    }

    public function getGalleryAttribute($value): array
    {
        $gallery = $this->parseJsonOrArrayAttribute($value);

        return array_values(array_filter(array_map(function ($url) {
            if (is_string($url) && str_contains($url, '/storage/')) {
                return '/storage/'.ltrim(Str::after($url, '/storage/'), '/');
            }

            return $url;
        }, $gallery)));
    }

    private function parseJsonOrArrayAttribute($value): array
    {
        if (is_array($value)) {
            return array_values(array_filter($value, fn ($item) => is_string($item) && trim($item) !== ''));
        }

        if (is_string($value) && trim($value) !== '') {
            $decoded = json_decode($value, true);
            if (is_string($decoded)) {
                $decoded = json_decode($decoded, true);
            }
            if (is_array($decoded)) {
                return array_values(array_filter($decoded, fn ($item) => is_string($item) && trim($item) !== ''));
            }

            return array_values(array_filter(array_map('trim', explode(',', $value))));
        }

        return [];
    }
}
