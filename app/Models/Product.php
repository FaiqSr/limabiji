<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_id',
        'slug',
        'category',
        'description',
        'description_id',
        'origin',
        'altitude',
        'process',
        'roast_level',
        'sca_score',
        'tasting_notes',
        'flavor_tags',
        'recommended_brews',
        'image',
        'base_price_200g',
        'price_500g',
        'price_1kg',
        'stock',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'tasting_notes' => 'array',
        'flavor_tags' => 'array',
        'recommended_brews' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'base_price_200g' => 'integer',
        'price_500g' => 'integer',
        'price_1kg' => 'integer',
        'stock' => 'integer',
    ];

    public function getNameForLocale(?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        if ($locale === 'id' && ! empty($this->name_id)) {
            return $this->name_id;
        }

        return $this->name;
    }

    public function getDescriptionForLocale(?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        if ($locale === 'id' && ! empty($this->description_id)) {
            return $this->description_id;
        }

        return $this->description ?? '';
    }

    public function getPriceForWeight(string $weight): int
    {
        return match ($weight) {
            '500g' => (int) $this->price_500g,
            '1kg' => (int) $this->price_1kg,
            default => (int) $this->base_price_200g,
        };
    }

    public function getFormattedPrice(string $weight = '200g'): string
    {
        return 'Rp '.number_format($this->getPriceForWeight($weight), 0, ',', '.');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
