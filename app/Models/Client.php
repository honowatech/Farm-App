<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model 
{

    protected $table = 'clients';
    public $timestamps = true;

    use SoftDeletes, HasFactory;

    protected $dates = ['deleted_at'];
    protected $fillable = array('nom', 'prenom', 'telephone', 'adresse', 'email', 'id_user');
    protected $visible = array('nom', 'prenom', 'telephone', 'adresse', 'email', 'id_user');

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'id_user');
    }
    public function client_facture(){
         return $this->hasMany('App\Models\Facture', 'id');
    }

}