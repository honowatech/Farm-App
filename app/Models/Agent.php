<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agent extends Model
{
    use BelongsToTenant;
    protected $table = 'agents';

    public $timestamps = true;

    use SoftDeletes;

    protected $fillable = ['nom', 'adresse', 'telephone', 'telephone_secondaire', 'statut'];

    protected $visible = ['nom', 'adresse', 'telephone', 'telephone_secondaire', 'statut'];
}
