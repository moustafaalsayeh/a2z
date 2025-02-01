<?php

namespace Modules\Review\Entities;

use App\Helpers\Filterable;
use Illuminate\Database\Eloquent\Model;
use Modules\APIAuth\Entities\User;
use Modules\Product\Entities\Product;

class Review extends Model
{
    use Filterable;

    protected $guarded = ['id'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rates()
    {
        return $this->hasMany(ReviewRate::class);
    }

    public function getRankAttribute()
    {
        $rates = $this->rates;
        if ($rates->count() > 0) {
            $rank = 0;
            foreach ($rates as $key => $rate) {
                $rank += $rate->rate;
            }
            return bcdiv($rank, $rates->count(), 1);
        }

        return 0;
    }
}
