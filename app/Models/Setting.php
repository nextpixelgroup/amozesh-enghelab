<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $primaryKey = 'key';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
        'description'
    ];

    protected $casts = [
        'value' => 'json',
    ];

    // Cache settings for 24 hours by default
    public $cacheTtl = 60 * 24;

    // Get setting value by key
    public static function getValue(string $key, $default = null)
    {
        return cache()->remember(
            "setting.{$key}",
            now()->addMinutes(self::first()->cacheTtl ?? 1440),
            function () use ($key, $default) {
                $setting = self::find($key);
                return $setting ? $setting->value : $default;
            }
        );
    }

    // Set setting value
    public static function setValue(string $key, $value, string $group = 'general', string $type = 'text', string $description = null): void
    {
        $setting = self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'group' => $group,
                'type' => $type,
                'description' => $description
            ]
        );
        // Clear cache
        cache()->forget("setting.{$key}");
    }

    // Get all settings as array
    public static function getAllSettings(): array
    {
        return self::all()
            ->mapWithKeys(fn($item) => [$item->key => $item->value])
            ->toArray();
    }

    // Get settings by group
    public static function getGroup(string $group): array
    {
        return self::where('group', $group)
            ->get()
            ->mapWithKeys(fn($item) => [$item->key => $item->value])
            ->toArray();
    }

    // Clear all settings cache
    public static function clearCache(): void
    {
        $keys = self::pluck('key');
        foreach ($keys as $key) {
            cache()->forget("setting.{$key}");
        }
    }
}
