<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food_Setting extends Model
{
    use HasFactory;

    protected $table = 'foods_setting'; // Spécifie le nom de la table

    // Uniquement les colonnes qui restent dans la table principale
    protected $fillable = ['id_type_animal', 'aliment'];

    /**
     * Relation : Un planning a plusieurs versions.
     */
    public function versions()
    {
        return $this->hasMany(FoodSettingVersion::class, 'food_setting_id');
    }

    /**
     * Relation : Obtenir la version actuellement active du planning.
     */
    public function activeVersion()
    {
        // Retourne la relation pour pouvoir chaîner des requêtes dessus
        return $this->hasOne(FoodSettingVersion::class, 'food_setting_id')->whereNull('ended_at');
    }

    /**
     * Relation : Obtenir les détails de la version actuellement active.
     */
    public function activeVersionsDetails()
    {
        // Similaire à activeVersion, mais retourne une collection
        return $this->hasMany(FoodSettingVersion::class, 'food_setting_id')->whereNull('ended_at');
    }

    // Relations pour obtenir les noms
    public function animalType()
    {
        return $this->belongsTo(Type_animal::class, 'id_type_animal');
    }

    public function alimentName()
    {
        return $this->belongsTo(Matiere_premiere::class, 'aliment');
    }
}
