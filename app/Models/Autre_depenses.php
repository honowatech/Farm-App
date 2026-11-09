<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Autre_depenses extends Model 
{

    protected $table = 'autre_depenses';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = array('montant', 'description', 'date_depense', 'id_saver');
    protected $visible = array('montant', 'description', 'date_depense', 'id_saver');

    public function client()
    {
        return $this->belongsTo('App\Models\User', 'id_saver');
    }

}