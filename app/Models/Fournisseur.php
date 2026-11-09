<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fournisseur extends Model
{
    protected $table = 'fournisseurs';

    public $timestamps = true;

    use SoftDeletes;

    protected $fillable = ['nom', 'adresse', 'telephone', 'telephone_secondaire', 'statut'];

    protected $visible = ['nom', 'adresse', 'telephone', 'telephone_secondaire', 'statut'];

    public function achats()
    {
        return $this->hasMany('App\Models\Achat', 'id_fournisseur');
    }

    public function infos()
    {
        return $this->hasMany('App\Models\Detail_fournisseur', 'id_fournisseur');
    }
}
