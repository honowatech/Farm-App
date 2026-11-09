<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Batiment extends Model
{
    protected $table = 'batiments';

    public $timestamps = true;

    use SoftDeletes;

    protected $fillable = ['nom', 'description'];

    protected $visible = ['nom', 'description'];

    public function nettoyages()
    {
        return $this->hasMany(Nettoyage::class);
    }
}
