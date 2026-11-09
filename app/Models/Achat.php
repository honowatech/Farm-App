<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Achat extends Model
{
    protected $table = 'achats';

    public $timestamps = true;

    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['id_stock_matiere_premiere', 'unite_achat', 'id_matiere_premiere', 'prix_unitaire', 'qte', 'prix_total', 'mode_de_paiement', 'id_fournisseur', 'date'];

    protected $visible = ['id_stock_matiere_premiere', 'unite_achat', 'id_matiere_premiere', 'prix_unitaire', 'qte', 'prix_total', 'mode_de_paiement', 'id_fournisseur', 'date'];

    public function matiere_premiere()
    {
        return $this->belongsTo('App\Models\Matiere_premiere', 'id_matiere_premiere');
    }

    public function stock_matiere_premiere()
    {
        return $this->belongsTo('App\Models\Stock_matiere_premiere', 'id_stock_matiere_premiere');
    }

    public function fournisseur()
    {
        return $this->belongsTo('App\Models\Fournisseur', 'id_fournisseur');
    }
}
