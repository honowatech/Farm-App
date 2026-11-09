<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Veterinaire extends Model
{
    protected $table = 'veterinaires';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = array('nom', 'adresse', 'telephone', 'telephone_secondaire', 'statut');
    protected $visible = array('nom', 'adresse', 'telephone', 'telephone_secondaire', 'statut');

    public function vaccinations()
    {
        return $this->hasMany('App\Models\Vaccination', 'id_veterinaire');
    }
}
