<?php

namespace App\Services;

use App\Models\Setting;

class SettingsService
{
    public function all()
    {
        return Setting::all();
    }

    public function get(string $key, $default = null)
    {
        return Setting::getValue($key, $default);
    }

    public function set(string $key, $value, string $group = 'general', string $type = 'text'): void
    {
        Setting::setValue($key, $value, $group, $type);
    }

    public function getGroup(string $group): array
    {
        return Setting::getGroup($group);
    }

    public function clearCache(): void
    {
        Setting::clearCache();
    }
}
