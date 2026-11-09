<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Ramassage extends Model
{
    protected $table = 'ramassages';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = array('id_bande', 'ids_derive', 'valeurs_derive', 'id_prestataire', 'id_saver', 'to_do_date', 'do_date', 'commentaire','statut','id_produit');
    protected $visible = array('id_bande', 'ids_derive', 'valeurs_derive', 'id_prestataire', 'id_saver', 'to_do_date', 'do_date', 'commentaire','statut','id_produit');

    public function bande()
    {
        return $this->belongsTo('App\Models\Bande', 'id_bande');
    }
    public function produit()
    {
        return $this->belongsTo('App\Models\Produit', 'id_produit');
    }
    public function enregistreur()
    {
        return $this->belongsTo('App\Models\User', 'id_saver');
    }
    
    //public function prestataire()
    //{
    //    return $this->belongsTo('App\Models\User', 'id_prestataire');
    //}

    public function agent_name()
    {
        return $this->belongsTo('App\Models\Agent', 'id_prestataire');
    }
}
