<?php

namespace Modules\Saves\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\APIAuth\Entities\User;
use Modules\Outlet\Entities\Outlet;

class Save extends Model
{
    protected $guarded = ['id'];

    public $timestamps = false;

    public function savable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function saveCollection()
    {
        return $this->belongsTo(SaveCollection::class);
    }
}
