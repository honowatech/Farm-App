<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fournisseur extends Model 
{

    protected $table = 'fournisseurs';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = array('nom', 'adresse', 'telephone', 'telephone_secondaire', 'statut');
    protected $visible = array('nom', 'adresse', 'telephone', 'telephone_secondaire', 'statut');

    public function achats()
    {
        return $this->hasMany('App\Models\Achat', 'id_fournisseur');
    }
    public function infos()
    {
        return $this->hasMany('App\Models\Detail_fournisseur', 'id_fournisseur');
    }

}