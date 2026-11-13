<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Typeusers extends Model
{
    protected $table = 'type_users';

    public $timestamps = true;

    protected $fillable = ['libelle'];

    protected $visible = ['libelle'];

    public function users()
    {
        return $this->hasMany('App\Models\Users', 'id_type_user');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_type_user', 'type_user_id', 'permission_id');
    }
}
