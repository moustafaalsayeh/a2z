<?php

namespace Modules\Search\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\APIAuth\Entities\User;

class BuyerDeliveringOutlet extends Model
{
    protected $fillable = ['user_id', 'outlets'];

    public $casts = [
        'outlets' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getOutletsIdsAttribute()
    {
        return explode(',', json_decode($this->outlets));
    }
}
