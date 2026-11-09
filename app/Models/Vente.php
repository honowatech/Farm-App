<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vente extends Model
{
    protected $table = 'ventes';

    public $timestamps = true;

    use SoftDeletes;

    protected $fillable = ['id_facture', 'id_saver', 'date_vente'];

    protected $visible = ['id_facture', 'id_saver', 'date_vente'];

    public function facture()
    {
        return $this->belongsTo('App\Models\Facture', 'id_facture');
    }

    public function saver()
    {
        return $this->belongsTo('App\Models\User', 'id_saver');
    }

    public function factures()
    {
        return $this->hasMany('App\Models\Facture', 'id');
    }
}
