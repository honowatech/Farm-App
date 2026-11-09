<?php

namespace Modules\Depenses\Entities;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Depense extends Model
{
    protected $fillable = ['montant','fournisseur','description','date_depense','id_saver','id_type_depense','motif_depense','id_compte']; // Les champs autorisés pour la création et la mise à jour
    protected $visible = array('montant','fournisseur', 'description', 'date_depense', 'id_saver','id_type_depense','motif_depense');
    protected $table = 'depense_depenses';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function categorie_depense()
    {
        return $this->belongsTo('Modules\Depenses\Entities\Categorie','id_categorie');
    }
}
