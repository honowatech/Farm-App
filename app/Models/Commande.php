<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Commande extends Model
{
    use BelongsToTenant;
    use LogsActivity;
    use SoftDeletes;

    public const STATUT_BROUILLON = 'brouillon';
    public const STATUT_CONFIRMEE = 'confirmee';
    public const STATUT_EN_PREPARATION = 'en_preparation';
    public const STATUT_PRET = 'prete';
    public const STATUT_ENCAISSEE = 'encaissee';
    public const STATUT_LIVREE = 'livree';
    public const STATUT_ANNULEE = 'annulee';

    /**
     * @var array<int, string>
     */
    public const STATUTS = [
        self::STATUT_BROUILLON,
        self::STATUT_CONFIRMEE,
        self::STATUT_EN_PREPARATION,
        self::STATUT_PRET,
        self::STATUT_ENCAISSEE,
        self::STATUT_LIVREE,
        self::STATUT_ANNULEE,
    ];

    protected $table = 'commandes';

    protected $fillable = [
        'matricule',
        'id_client',
        'statut',
        'total_ht',
        'tva',
        'total_ttc',
        'id_saver',
        'date_commande',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'id_client');
    }

    public function lignes(): HasMany
    {
        return $this->hasMany(LigneCommande::class, 'id_commande');
    }

    public function saver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_saver');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
