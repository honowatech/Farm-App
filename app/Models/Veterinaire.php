<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Veterinaire extends Model
{
    use BelongsToTenant;
    protected $table = 'veterinaires';

    public $timestamps = true;

    use SoftDeletes;

    protected $fillable = ['nom', 'adresse', 'telephone', 'telephone_secondaire', 'statut'];

    protected $visible = ['nom', 'adresse', 'telephone', 'telephone_secondaire', 'statut'];

    public function vaccinations()
    {
        return $this->hasMany('App\Models\Vaccination', 'id_veterinaire');
    }
}
