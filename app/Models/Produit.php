<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produit extends Model
{
    protected $table = 'produits';

    public $timestamps = true;

    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['id_type_animal', 'prix_unitaire', 'nom', 'description', 'statut', 'id_type_produit'];

    protected $visible = ['id_type_animal', 'prix_unitaire', 'nom', 'description', 'statut', 'id_type_produit'];

    public function type_animal()
    {
        return $this->belongsTo('App\Models\Type_animal', 'id_type_animal', 'id');
    }

    public function mouvements_de_stock()
    {
        return $this->hasMany('App\Models\Stock_produit', 'id_produit');
    }

    public function ramassages()
    {
        return $this->hasMany('App\Models\Ramassage', 'id_produit');
    }

    public function derives()
    {
        return $this->hasMany('App\Models\Derive_produit', 'id_produit');
    }

    public function produit_type()
    {
        return $this->BelongsTo('App\Models\type_produit', 'id_type_produit');
    }
}
