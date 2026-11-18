<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    use BelongsToTenant;
    protected $table = 'app_settings';

    protected $fillable = ['key', 'value', 'group'];

    protected $visible = ['key', 'value', 'group'];
}
