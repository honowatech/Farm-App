<?php

namespace Modules\Depenses\Entities;

use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    protected $fillable = ['nom','description','frequence','description','prix_unitaire'];
    protected $visible = array('nom','description','frequence','description','prix_unitaire');
    protected $table = 'depense_categorie_depenses';
    public $timestamps = true;

 public function categorie()
    {
       return $this->hasMany('Modules\Depenses\Entities\Depense',);
    }
}
