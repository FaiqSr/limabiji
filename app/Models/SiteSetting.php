<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'key', 'value', 'locale', 'group',
    ];

    protected $casts = [
        'value' => 'array',
    ];

    /**
     * Get a setting value by key and optional locale.
     */
    public static function get(string $key, ?string $locale = null, mixed $default = null): mixed
    {
        $query = static::where('key', $key);

        if ($locale) {
            $setting = $query->where('locale', $locale)->first()
                       ?? $query->whereNull('locale')->first();
        } else {
            $setting = $query->first();
        }

        return $setting?->value ?? $default;
    }

    /**
     * Set a setting value.
     */
    public static function set(string $key, mixed $value, ?string $locale = null, string $group = 'general'): void
    {
        static::updateOrCreate(
            ['key' => $key, 'locale' => $locale],
            ['value' => $value, 'group' => $group]
        );
    }
}
