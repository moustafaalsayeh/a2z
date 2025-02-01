<?php

namespace Modules\GlobalSetting\Entities;

use Illuminate\Database\Eloquent\Model;

class GlobalSetting extends Model
{
    protected $fillable = ['name', 'value', 'type'];
}
