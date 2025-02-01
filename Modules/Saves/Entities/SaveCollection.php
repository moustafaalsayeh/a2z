<?php

namespace Modules\Saves\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\APIAuth\Entities\User;
use Modules\Media\Helpers\Mediable;

class SaveCollection extends Model
{
    use Mediable;

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function saves()
    {
        return $this->hasMany(Save::class);
    }
}
