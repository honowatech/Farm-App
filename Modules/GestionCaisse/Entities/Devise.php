<?php

namespace Modules\GestionCaisse\Entities;

use Illuminate\Database\Eloquent\Model;

class Devise extends Model 
{

    protected $table = 'gestion_caisse_devises';
    public $timestamps = true;
    protected $fillable = array('code', 'symbole', 'nom');
    protected $visible = array('code', 'symbole', 'nom');

    public function devise_compte()
    {
        return $this->hasMany('Modules\GestionCaisse\Entities\Compte');
    }

}