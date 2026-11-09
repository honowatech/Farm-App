<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type_produit extends Model
{
    protected $table = 'type_produits';

    public $timestamps = true;

    use HasFactory;

    protected $dates = ['deleted_at'];

    protected $fillable = ['nom'];

    protected $visible = ['nom'];

    public function type_produit()
    {
        return $this->hasMany('App\Models\Produit', 'id_produit');
    }
}
