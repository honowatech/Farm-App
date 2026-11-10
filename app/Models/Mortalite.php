<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Mortalite extends Model
{
    use LogsActivity, SoftDeletes;

    protected $table = 'mortalites';

    protected $fillable = ['id_bande', 'date', 'nombre_morts', 'id_cause', 'observation', 'id_saver'];

    protected $visible = ['id_bande', 'date', 'nombre_morts', 'id_cause', 'observation', 'id_saver'];

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    public function bande()
    {
        return $this->belongsTo(Bande::class, 'id_bande');
    }

    public function cause()
    {
        return $this->belongsTo(MortaliteCause::class, 'id_cause');
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
