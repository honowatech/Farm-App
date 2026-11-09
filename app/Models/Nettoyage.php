<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Nettoyage extends Model
{
    use HasFactory;
    use SoftDeletes;

     protected $table = 'nettoyages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_agent', 
        'id_batiment',
        'date', 
        'verif', 
        'description'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'verif' => 'boolean',
    ];

    /**
     * relation avec les differerent models
     */

    public function agents()
    {
        return $this->hasMany(Agent::class, 'id', 'id_agent');
    }
    
    Public function batiment()
    {
        return $this->belongsTo(Batiment::class, 'id_batiment');
    }
}
