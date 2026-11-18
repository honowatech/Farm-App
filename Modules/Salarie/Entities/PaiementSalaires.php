<?php

namespace Modules\Salarie\Entities;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaiementSalaires extends Model
{
    use BelongsToTenant;
    protected $table = 'paiement_salaires';

    public $timestamps = true;

    use SoftDeletes;

    protected $fillable = ['type', 'date_paiement', 'telephone', 'id_salarie', 'date_debut', 'date_fin', 'montant', 'description'];

    protected $visible = ['type', 'date_paiement', 'telephone', 'id_salarie', 'date_debut', 'date_fin', 'montant', 'description'];

    public function salarie()
    {
        return $this->belongsTo('Modules\Salarie\Entities\Salaries', 'id_salarie');
    }
}
