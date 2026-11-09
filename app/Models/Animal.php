<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Animal extends Model
{
    protected $table = 'animals';

    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['id_type_animal', 'id_bande', 'age', 'poids', 'prix_dachat', 'prix_de_vente', 'statut_animal', 'numero_animal'];

    protected $visible = ['id_type_animal', 'id_bande', 'age', 'poids', 'prix_dachat', 'prix_de_vente', 'statut_animal', 'numero_animal'];

    public function type_animal()
    {
        return $this->belongsTo('App\Models\Type_animal', 'id_type_animal');
    }

    public function bande()
    {
        return $this->belongsTo('App\Models\Bande', 'id_bande');
    }
}
