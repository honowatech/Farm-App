<?php

namespace Modules\GestionCaisse\Entities;

use Illuminate\Database\Eloquent\Model;

class Compte extends Model
{

    protected $table = 'gestion_caisse_comptes';
    public $timestamps = true;
    protected $fillable = array('nom', 'type_account','numero_compte', 'nom_banque', 'initial_balance', 'current_balance', 'currency_id');
    protected $visible = array('nom', 'type_account','numero_compte', 'nom_banque', 'initial_balance', 'current_balance', 'currency_id');

    public function compte_devise()
    {
        return $this->belongsTo('Modules\GestionCaisse\Entities\Devise', 'currency_id');
    }

}
