<?php

namespace Modules\Product\Entities;

use App\Helpers\Filterable;
use Modules\APIAuth\Entities\User;
use Modules\Outlet\Entities\Outlet;
use Modules\APIAuth\Helpers\Helpers;
use Illuminate\Database\Eloquent\Model;
use Modules\Product\Helpers\ProductItemHelpers;

class Cart extends Model
{
    use Filterable;

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function getCartQuantityAttribute()
    {
        return ProductItemHelpers::ItemableQuantity($this);
    }

    public function getCartTotalPriceAttribute()
    {
        return ProductItemHelpers::ItemableTotalPrice($this);
    }

    public function getCurrencyAttribute()
    {
        $hepers = new Helpers();
        return $hepers->getCurrency();
    }
}
