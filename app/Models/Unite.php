<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unite extends Model
{
    use BelongsToTenant;
    protected $table = 'unite';

    public $timestamps = true;

    use SoftDeletes;

    protected $fillable = ['libelle', 'abrege'];

    protected $visible = ['libelle', 'abrege'];

    public function derives()
    {
        return $this->hasMany('App\Models\Derive_produit', 'id_unite');
    }

    public function nutritions()
    {
        return $this->hasMany(Nutrition::class, 'id_unite');
    }

    public function foodSettingVersions()
    {
        return $this->hasMany(FoodSettingVersion::class, 'id_unite');
    }
}
