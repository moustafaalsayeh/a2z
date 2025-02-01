<?php

namespace Modules\Country\Entities;

use App\Helpers\Translatable;
use Illuminate\Database\Eloquent\Model;
use Modules\APIAuth\Entities\User;

class Country extends Model
{
    protected $guarded = ['id'];

    public $timestamps = false;

    // public $translatedAttributes = [
    //     'name'
    // ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function cities()
    {
        return $this->hasMany(City::class);
    }

}
