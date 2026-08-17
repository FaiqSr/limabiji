<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InnovationStep extends Model
{
    use HasFactory;

    protected $fillable = [
        'step_number',
        'title',
        'title_id',
        'description',
        'description_id',
        'details',
        'details_id',
        'image',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Scope for active innovation steps.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordering steps.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order')->orderBy('id');
    }

    /**
     * Get title based on locale.
     */
    public function getTitleForLocale(?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();

        if ($locale === 'id' && ! empty($this->title_id)) {
            return $this->title_id;
        }

        return $this->title ?? '';
    }

    /**
     * Get description based on locale.
     */
    public function getDescriptionForLocale(?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();

        if ($locale === 'id' && ! empty($this->description_id)) {
            return $this->description_id;
        }

        return $this->description ?? '';
    }

    /**
     * Get detail tags based on locale.
     */
    public function getDetailsForLocale(?string $locale = null): ?string
    {
        $locale = $locale ?? app()->getLocale();

        if ($locale === 'id' && ! empty($this->details_id)) {
            return $this->details_id;
        }

        return $this->details ?? '';
    }

    /**
     * Accessor for formatted step number (e.g. '01', '02').
     */
    public function getFormattedStepNumberAttribute(): string
    {
        if (! empty($this->step_number)) {
            return str_pad($this->step_number, 2, '0', STR_PAD_LEFT);
        }

        return str_pad((string) ($this->order ?: 1), 2, '0', STR_PAD_LEFT);
    }
}
