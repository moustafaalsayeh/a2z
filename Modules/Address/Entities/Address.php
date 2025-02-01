<?php

namespace Modules\Address\Entities;

use App\Helpers\Filterable;
use Modules\Country\Entities\City;
use Modules\Country\Entities\Country;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use Filterable;
    
    protected $guarded = ['id'];

    protected $casts = [
        'lat' => 'double',
        'lng' => 'double'
    ];

    public function addressable()
    {
        return $this->morphTo();
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
