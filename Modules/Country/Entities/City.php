<?php

namespace Modules\Country\Entities;

use Modules\APIAuth\Entities\User;
use Illuminate\Database\Eloquent\Model;
use Modules\APIAuth\Helpers\Helpers;

class City extends Model
{
    protected $guarded = ['id'];

    public $timestamps = false;

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function getNameTranslationAttribute()
    {
        $locale = Helpers::getLocale();

        foreach (json_decode($this->alternateNames) as $key => $value) {
            if($value->lang == strtolower($locale))
            {
                return $value->name;
            }
        }
        
        return $this->name;
    }
}
