<?php

namespace Modules\Order\Entities;

use App\Helpers\Filterable;
use Carbon\Carbon;
use Modules\APIAuth\Entities\User;
use Modules\Outlet\Entities\Outlet;
use Modules\APIAuth\Helpers\Helpers;
use Modules\Product\Entities\CartItem;
use Illuminate\Database\Eloquent\Model;
use Modules\Address\Helpers\Addressable;
use Modules\Product\Helpers\ProductItemHelpers;

class Order extends Model
{
    use Filterable, Addressable;

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }

    public function deliveryMan()
    {
        return $this->belongsTo(User::class, 'delivery_man_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            $model->addresses()->delete();
            $model->items()->delete();
        });
    }

    public function getOrderQuantityAttribute()
    {
        return ProductItemHelpers::ItemableQuantity($this);
    }

    public function getOrderTotalPriceAttribute()
    {
        return ProductItemHelpers::ItemableTotalPrice($this);
    }

    public function getCurrencyAttribute()
    {
        $hepers = new Helpers();
        return $hepers->getCurrency();
    }

    public function setStatusAttribute($value)
    {
        switch ($value) {
            case 'accepted':
                $this->attributes['accepted_at'] = Carbon::now();
                break;
            case 'delivering':
                $this->attributes['delivering_at'] = Carbon::now();
                break;
            case 'completed':
                $this->attributes['completed_at'] = Carbon::now();
                break;
        }
        $this->attributes['status'] = $value;
    }

    public function getSecondsLeftForCancelAttribute()
    {
        return Carbon::now()->lt($this->created_at->addMinutes(5)) && $this->status == 'waiting' ?
            Carbon::now()->diffInSeconds($this->created_at->addMinutes(5)) : 0;
    }
}
