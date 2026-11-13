<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Seance_de_vaccination extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'seance_de_vaccination';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_veterinaire',
        'id_bande',
        'vaccin_attribuer',
        'verification',
        'description',
        'cout',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected function casts(): array
    {
        return [
            'verification' => 'boolean', // Convertit automatiquement la colonne "verification" en un type booléen (true/false)
        ];
    }

    /**
     * Get the veterinarian associated with the vaccination session.
     */
    public function veterinaire()
    {
        return $this->belongsTo(Veterinaire::class, 'id_veterinaire');
    }

    public function bande()
    {
        return $this->belongsTo(Bande::class, 'id_bande');
    }
}
