<?php

namespace Modules\Depenses\Entities;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    use BelongsToTenant;
    protected $fillable = ['nom', 'description', 'frequence', 'description', 'prix_unitaire'];

    protected $visible = ['nom', 'description', 'frequence', 'description', 'prix_unitaire'];

    protected $table = 'depense_categorie_depenses';

    public $timestamps = true;

    public function categorie()
    {
        return $this->hasMany('Modules\Depenses\Entities\Depense');
    }
}
