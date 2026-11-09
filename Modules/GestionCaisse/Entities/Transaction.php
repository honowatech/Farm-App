<?php

namespace Modules\GestionCaisse\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\GestionCaisse\Entities\Compte;
use Modules\Depenses\Entities\Depense;
use App\Models\User;

class Transaction extends Model
{
    protected $table = 'gestion_caisse_transactions';

    protected $fillable = [
        'compte_id',
        'transaction_type',
        'montant',
        'transaction_date',
        'description',
        'reference_number',
        'user_id',
        'related_transaction_id',
        'id_depense',
    ];

    protected $casts = [
        'transaction_date' => 'datetime',
    ];

    // Define relationships if needed
    public function compte()
    {
        return $this->belongsTo(Compte::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function depense()
    {
        return $this->belongsTo(Depense::class, 'id_depense');
    }

    public function relatedTransaction()
    {
        return $this->belongsTo(Transaction::class, 'related_transaction_id');
    }

    // Additional methods can be added here as needed
}
