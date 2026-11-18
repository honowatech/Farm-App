<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class MortaliteCause extends Model
{
    use BelongsToTenant;
    protected $table = 'mortalite_causes';

    protected $fillable = ['libelle', 'statut'];

    protected $visible = ['libelle', 'statut'];

    public function mortalites()
    {
        return $this->hasMany(Mortalite::class, 'id_cause');
    }
}
