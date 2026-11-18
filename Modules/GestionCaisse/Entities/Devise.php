<?php

namespace Modules\GestionCaisse\Entities;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Devise extends Model
{
    use BelongsToTenant;
    protected $table = 'gestion_caisse_devises';

    public $timestamps = true;

    protected $fillable = ['code', 'symbole', 'nom'];

    protected $visible = ['code', 'symbole', 'nom'];

    public function devise_compte()
    {
        return $this->hasMany('Modules\GestionCaisse\Entities\Compte');
    }
}
