<?php

namespace Modules\Salarie\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Salaries extends Model
{
    protected $table = 'salaries';

    public $timestamps = true;

    use HasFactory, SoftDeletes;

    protected $fillable = ['nom', 'adresse', 'id_saver', 'telephone', 'telephone_secondaire', 'accumulateur', 'type_periode', 'salaire', 'date_debut_contrat', 'date_dernier_paiement', 'statut'];

    protected $visible = ['nom', 'adresse', 'id_saver', 'telephone', 'telephone_secondaire', 'accumulateur', 'type_periode', 'salaire', 'date_debut_contrat', 'date_dernier_paiement', 'statut'];

    public function Paiements()
    {
        return $this->hasMany('Modules\Salarie\Entities\Paiement_salaires', 'id_salarie');
    }

    public function enregistreur()
    {
        return $this->belongsTo('App\Models\User', 'id_saver');
    }
}
