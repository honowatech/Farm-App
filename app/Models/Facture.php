<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Facture extends Model
{
    protected $table = 'factures';

    public $timestamps = true;

    use SoftDeletes;

    protected $fillable = ['date_facturation', 'total_ht', 'id_client', 'tva', 'total_ttc', 'matricule'];

    protected $visible = ['date_facturation', 'total_ht', 'id_client', 'tva', 'total_ttc', 'matricule'];

    public function lignes_facture()
    {
        return $this->hasMany('App\Models\Ligne_facture', 'id_facture');
    }

    public function client_facture()
    {
        return $this->belongsTo('App\Models\Client', 'id_client');
    }

    public function vente_facture()
    {
        return $this->belongsTo('App\Models\Vente', 'id_vente');
    }
}
