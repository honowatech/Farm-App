<?php

namespace Modules\GestionCaisse\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transfert extends Model
{
    use HasFactory;
    protected $table = 'gestion_caisse_transferts';
    public $timestamps = true;
    protected $fillable = ['from_account_id','to_account_id','amount','transfer_date','description','user_id'];
    protected $visible = ['from_account_id','to_account_id','amount','transfer_date','description','user_id'];
    
    public function sender_account(){
        return $this->belongsTo('Modules\GestionCaisse\Entities\Compte', 'from_account_id');
    }
    public function receiver_account(){
        return $this->belongsTo('Modules\GestionCaisse\Entities\Compte', 'to_account_id');
    }
    public function receiver_user(){
        return $this->belongsTo('App\Models\Users','user_id');
    }
    protected static function newFactory()
    {
        return \Modules\GestionCaisse\Database\factories\TransfertFactory::new();
    }
}
