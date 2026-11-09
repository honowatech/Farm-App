<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Logo extends Model
{
    use HasFactory;

    protected $table = 'logo';

    public $timestamps = true;

    use SoftDeletes;

    protected $fillable = ['path'];

    protected $visible = ['path'];
}
