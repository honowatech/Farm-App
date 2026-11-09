<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Typeusers extends Model
{
    protected $table = 'type_users';
    public $timestamps = true;
    protected $fillable = array('libelle');
    protected $visible = array('libelle');

    public function users()
    {
        return $this->hasMany('App\Models\Users', 'id_type_user');
    }
}