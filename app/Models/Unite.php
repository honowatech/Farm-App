<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unite extends Model
{
    protected $table = 'unite';

    public $timestamps = true;

    use SoftDeletes;

    protected $fillable = ['libelle', 'abrege'];

    protected $visible = ['libelle', 'abrege'];

    public function derives()
    {
        return $this->hasMany('App\Models\Derive_produit', 'id_unite');
    }
}
