<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = [
        'name', 'company', 'content', 'content_id',
        'rating', 'image', 'is_featured', 'order',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_featured' => 'boolean',
        'order' => 'integer',
    ];

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public function getContentForLocale(string $locale = 'en'): string
    {
        if ($locale === 'id' && $this->content_id) {
            return $this->content_id;
        }

        return $this->content;
    }
}
