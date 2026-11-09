<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detail_fournisseur extends Model
{
    protected $table = 'detail_fournisseur';
    public $timestamps = true;

    use HasFactory;

    protected $dates = ['deleted_at'];
    protected $fillable = array('id_fournisseur', 'id_matiere_premiere');
    protected $visible = array('id_fournisseur', 'id_matiere_premiere');

    public function matiere_premiere()
    {
        return $this->belongsTo('App\Models\Matiere_premiere', 'id_matiere_premiere');
    }
    public function fournisseur()
    {
        return $this->belongsTo('App\Models\Fournisseur', 'id_fournisseur');
    }
}