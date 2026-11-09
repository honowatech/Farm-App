<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stock_produit extends Model
{
    protected $table = 'stock_produits';

    public $timestamps = true;

    use SoftDeletes;

    protected $fillable = ['id_produit', 'id_derive_produit', 'type_mouvement', 'qte', 'dernier_stock', 'nouveau_stock', 'date_entree', 'date_sortie'];

    protected $visible = ['id_produit', 'id_derive_produit', 'type_mouvement', 'qte', 'dernier_stock', 'nouveau_stock', 'date_entree', 'date_sortie'];

    public function produit()
    {
        return $this->belongsTo('App\Models\Produit', 'id_produit');
    }

    public function derive_produit()
    {
        return $this->belongsTo('App\Models\Produit', 'id_derive_produit');
    }
}
