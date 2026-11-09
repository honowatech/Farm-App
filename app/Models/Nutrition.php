<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nutrition extends Model
{
    use HasFactory;

    protected $table = 'nutritions';

    public $timestamps = true;

    protected $fillable = ['id_bande', 'id_aliment', 'qte', 'date', 'id_unite', 'nombre_de_passages', 'derniere_nutrition', 'compteur_nutrition',  'prochaine_nutrition', 'statut_nutrition'];

    protected $visible = ['id_bande', 'id_aliment', 'qte', 'date', 'id_unite', 'nombre_de_passages', 'derniere_nutrition', 'compteur_nutrition',  'prochaine_nutrition', 'statut_nutrition'];

    public function bande_name()
    {

        return $this->belongsTo(Bande::class, 'id_bande');
    }

    public function aliment_name()
    {

        return $this->belongsTo(Matiere_premiere::class, 'id_aliment');
    }

    public function unite()
    {

        return $this->belongsTo(Unite::class, 'id_unite');
    }
}
