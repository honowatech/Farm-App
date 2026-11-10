<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgrammeVaccination extends Model
{
    protected $table = 'programmes_vaccination';

    protected $fillable = ['id_type_animal', 'age_jour', 'nom_vaccin', 'rappel_jours', 'description'];

    protected $visible = ['id_type_animal', 'age_jour', 'nom_vaccin', 'rappel_jours', 'description'];

    public function typeAnimal()
    {
        return $this->belongsTo(Type_animal::class, 'id_type_animal');
    }
}
