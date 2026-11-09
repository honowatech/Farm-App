<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Batiment extends Model 
{

    protected $table = 'batiments';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = array('nom', 'description');
    protected $visible = array('nom', 'description');

    Public function nettoyages()
    {
        return $this->hasMany(Nettoyage::class);
    }

}