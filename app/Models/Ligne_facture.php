<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ligne_facture extends Model
{
    protected $table = 'ligne_factures';

    public $timestamps = true;

    use SoftDeletes;

    protected $fillable = ['id_facture', 'id_produit', 'type_vente', 'id_stock_produit', 'mode_paiement', 'id_bande', 'qte', 'prix_unitaire', 'total_ligne'];

    protected $visible = ['id_facture', 'id_produit', 'type_vente', 'id_stock_produit', 'mode_paiement', 'id_bande', 'qte', 'prix_unitaire', 'total_ligne'];

    public function bande()
    {
        return $this->belongsTo('App\Models\Bande', 'id_bande');
    }

    public function produit()
    {
        return $this->belongsTo('App\Models\Produit', 'id_produit');
    }

    public function stock_produit()
    {
        return $this->belongsTo('App\Models\Stock_produit', 'id_stock_produit');
    }
}
