<?php

namespace Modules\Outlet\Entities;

use App\Helpers\Filterable;
use Modules\APIAuth\Helpers\Helpers;
use Illuminate\Database\Eloquent\Model;

class DeliveryArea extends Model
{
    use Filterable;

    protected $guarded = ['id'];

    public $timestamps = false;

    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }

    public function getDeliveryFeesAttribute($value)
    {
        $hepers = new Helpers();
        return $hepers->moneyGetter($value);
    }

    public function setDeliveryFeesAttribute($value)
    {
        $this->attributes['delivery_fees'] = Helpers::moneySetter($value);
    }

    public function getPointsAttribute($value)
    {
        return json_decode($value);
    }

    public function setPointsAttribute($value)
    {
        $this->attributes['points'] = json_encode($value);
    }
}
