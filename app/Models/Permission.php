<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = ['name', 'label', 'group'];

    public function typeUsers()
    {
        return $this->belongsToMany(Typeusers::class, 'permission_type_user', 'permission_id', 'type_user_id');
    }
}
