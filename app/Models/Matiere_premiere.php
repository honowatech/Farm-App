<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Matiere_premiere extends Model
{
    protected $table = 'matiere_premieres';

    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    // protected $fillable = array('type','date_paiement','telephone','id_salarie','date_debut','date_fin','montant','description');
    // protected $visible = array('type','date_paiement','telephone','id_salarie','date_debut','date_fin','montant','description');

    public function mouvements_de_stock()
    {
        return $this->hasMany('App\Models\Stock_matiere_premiere', 'id_matiere_premiere');
    }

    public function infos()
    {
        return $this->hasMany('App\Models\Detail_fournisseur', 'id_matiere_premiere');
    }
}
