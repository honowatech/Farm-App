<?php

namespace App\Services;

use App\Models\AppSetting;

class SettingService
{
    public function get(string $key, $default = null)
    {
        $row = AppSetting::where('key', $key)->first();

        return $row !== null ? $row->value : $default;
    }

    public function set(string $key, $value): void
    {
        AppSetting::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    public function setMany(array $values): void
    {
        foreach ($values as $key => $value) {
            $this->set($key, $value);
        }
    }
}
