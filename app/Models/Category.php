<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    protected $fillable = [
        'name',
        'name_id',
        'slug',
    ];

    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'article_category')
            ->withTimestamps();
    }

    public function getNameForLocale(?string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();

        if ($locale === 'id' && ! empty($this->name_id)) {
            return $this->name_id;
        }

        return $this->name ?? '';
    }
}
