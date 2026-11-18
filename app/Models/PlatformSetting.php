<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Réglages globaux de la plateforme (hors contexte tenant), gérés par le
 * super administrateur. Stockage clé/valeur.
 */
class PlatformSetting extends Model
{
    protected $table = 'platform_settings';

    protected $fillable = ['key', 'value'];

    public static function get(string $key, $default = null)
    {
        return static::where('key', $key)->value('value') ?? $default;
    }

    public static function set(string $key, $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
