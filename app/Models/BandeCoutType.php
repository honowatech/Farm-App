<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BandeCoutType extends Model
{
    protected $table = 'bande_cout_types';

    protected $fillable = ['libelle', 'statut'];

    protected $visible = ['libelle', 'statut'];

    public function couts()
    {
        return $this->hasMany(BandeCout::class, 'id_type_cout');
    }
}
