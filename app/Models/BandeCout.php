<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class BandeCout extends Model
{
    use LogsActivity, SoftDeletes;

    protected $table = 'bande_couts';

    protected $fillable = ['id_bande', 'id_type_cout', 'montant', 'date_debut', 'date_fin', 'description', 'id_saver'];

    protected $visible = ['id_bande', 'id_type_cout', 'montant', 'date_debut', 'date_fin', 'description', 'id_saver'];

    protected function casts(): array
    {
        return [
            'date_debut' => 'date',
            'date_fin' => 'date',
        ];
    }

    public function bande()
    {
        return $this->belongsTo(Bande::class, 'id_bande');
    }

    public function typeCout()
    {
        return $this->belongsTo(BandeCoutType::class, 'id_type_cout');
    }

    public function saver()
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
