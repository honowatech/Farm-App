<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Derive_produit extends Model
{
    protected $table = 'derive_produit';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = array('noms', 'id_produit', 'valeurs_derive', 'min', 'max', 'id_unite','prix_unitaire','statut');
    protected $visible = array('noms', 'id_produit', 'valeurs_derive', 'min', 'max', 'id_unite','prix_unitaire','statut');

    public function produit()
    {
        return $this->belongsTo('App\Models\Produit', 'id_produit');
    }
    public function mouvements_de_stock()
    {
        return $this->hasMany('App\Models\Stock_produit', 'id_derive_produit');
    }
    public function unite()
    {
        return $this->belongsTo('App\Models\Unite', 'id_unite');
    }
}
