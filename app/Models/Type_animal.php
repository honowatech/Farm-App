<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Type_animal extends Model
{
    protected $table = 'type_animals';

    public $timestamps = true;

    use HasFactory , SoftDeletes;

    protected $fillable = ['id_type_animal', 'nom', 'description', 'type_reproduction'];

    protected $visible = ['id_type_animal', 'nom', 'description', 'type_reproduction'];

    public function produits()
    {
        return $this->hasMany('App\Models\Produit', 'id_type_animal');
    }

    public function foodsSettings()
    {
        return $this->hasMany('App\Models\Food_Setting', 'id_type_animal');
    }
}
