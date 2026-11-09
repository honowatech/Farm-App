<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodSettingVersion extends Model
{
    use HasFactory;

    protected $table = 'food_setting_versions';

    protected $fillable = [
        'food_setting_id',
        'age_min',
        'age_max',
        'quantite_aliment',
        'id_unite',
        'frequency',
        'period',
        'activated_at',
        'ended_at',
    ];

    protected $casts = [
        'activated_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    /**
     * Relation : Une version appartient à un planning parent.
     */
    public function foodSetting()
    {
        return $this->belongsTo(Food_Setting::class, 'food_setting_id');
    }

    /**
     * Relation : Pour obtenir le nom de l'unité.
     */
    public function unite()
    {
        return $this->belongsTo(Unite::class, 'id_unite');
    }
}
