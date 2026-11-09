<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unite extends Model
{
    protected $table = 'unite';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = array('libelle', 'abrege');
    protected $visible = array('libelle', 'abrege');

    public function derives()
    {
        return $this->hasMany('App\Models\Derive_produit', 'id_unite');
    }
}
