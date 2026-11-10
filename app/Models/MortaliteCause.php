<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MortaliteCause extends Model
{
    protected $table = 'mortalite_causes';

    protected $fillable = ['libelle', 'statut'];

    protected $visible = ['libelle', 'statut'];

    public function mortalites()
    {
        return $this->hasMany(Mortalite::class, 'id_cause');
    }
}
