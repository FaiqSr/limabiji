<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExportDestination extends Model
{
    protected $fillable = [
        'name',
        'name_id',
        'country_code',
        'longitude',
        'latitude',
        'description',
        'description_id',
        'is_active',
        'order',
    ];

    protected $casts = [
        'longitude' => 'float',
        'latitude' => 'float',
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getNameForLocale(?string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();
        if ($locale === 'id' && ! empty($this->name_id)) {
            return $this->name_id;
        }

        return $this->name;
    }

    public function getDescriptionForLocale(?string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();
        if ($locale === 'id' && ! empty($this->description_id)) {
            return $this->description_id;
        }

        return $this->description ?? '';
    }
}
