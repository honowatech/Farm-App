<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agent extends Model
{
    protected $table = 'agents';

    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['nom', 'adresse', 'telephone', 'telephone_secondaire', 'statut'];

    protected $visible = ['nom', 'adresse', 'telephone', 'telephone_secondaire', 'statut'];
}
