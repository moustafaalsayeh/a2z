<?php

namespace Modules\InviteUser\Entities;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    protected $guarded = ['id'];

    public $timestamps = false;
}
