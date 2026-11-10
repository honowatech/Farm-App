<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Stock_matiere_premiere extends Model
{
    protected $table = 'stock_matiere_premieres';

    public $timestamps = true;

    use LogsActivity;
    use SoftDeletes;

    protected $fillable = ['id_matiere_premiere', 'type_mouvement', 'quantite', 'dernier_stock', 'nouveau_stock', 'date_entree', 'date_sortie', 'motif'];

    protected $visible = ['id_matiere_premiere', 'type_mouvement', 'quantite', 'dernier_stock', 'nouveau_stock', 'date_entree', 'date_sortie', 'motif'];

    public function matiere_premiere()
    {
        return $this->belongsTo('App\Models\Matiere_premiere', 'id_matiere_premiere');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
