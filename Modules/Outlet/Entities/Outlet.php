<?php

namespace Modules\Outlet\Entities;

use DateTime;
use DateInterval;
use DateTimeZone;
use GuzzleHttp\Client;
use App\Helpers\Filterable;
use App\Helpers\Translatable;
use Modules\Menu\Entities\Menu;
use Modules\Saves\Entities\Save;
use Modules\Order\Entities\Order;
use Modules\APIAuth\Entities\User;
use Modules\Product\Entities\Cart;
use Modules\Media\Helpers\Mediable;
use Modules\APIAuth\Helpers\Helpers;
use Modules\Product\Entities\Product;
use Illuminate\Database\Eloquent\Model;
use Modules\Address\Helpers\Addressable;
use Modules\ProductSpecification\Entities\ProductSpecification;

class Outlet extends Model
{
    use Mediable, Translatable, Filterable, Addressable;

    protected $guarded = ['id'];

    public $translatedAttributes = [
        'name',
        'info'
    ];

    public function workingHours()
    {
        return $this->hasMany(WorkHour::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function productSpecifications()
    {
        return $this->morphToMany(ProductSpecification::class, 'specificable');
    }

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function saves()
    {
        return $this->morphMany(Save::class, 'savable');
    }

    public function deliveryAreas()
    {
        return $this->hasMany(DeliveryArea::class);
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
            $model->deleteAllMedia();
            $model->addresses()->delete();
            $model->saves()->delete();
            $model->productSpecifications()->delete();
        });
    }

    public function getAvailableAttribute()
    {
        $day = date('l');
        $time = (int) date('His');
        // dd($day, $time);
        foreach ($this->workingHours as $key => $value) {
            if($value->day == strtolower($day) && $time > (int) str_replace(':', '', $value->time_from) && $time < (int) str_replace(':', '', $value->time_to))
            {
                return true;
                break;
            }
        }
        return false;
    }

    public function getIsSavedAttribute()
    {
        if (auth('api')->user() && $save = auth('api')->user()->saves()->where('savable_type', Outlet::class)->where('savable_id', $this->id)->first()) {
            return [
                'save_id' => $save->id,
                'collection_id' => $save->saveCollection ? $save->saveCollection->id : null,
            ];
        }
        return [
            'save_id' => 0,
            'collection_id' => 0,
        ];
    }

    public function getDeliveryAreaInfoAttribute()
    {
        if(!(auth('api')->user() && $user_address = auth('api')->user()->primary_address) && !request()->query('location'))
        {
            return null;
        }

        $location = request()->query('location') ? explode(',', request()->query('location')) : [$user_address->lat, $user_address->lng];

        foreach ($this->deliveryAreas as $key => $area) {
            if(Helpers::insidePolygon($location, $area['points']))
            {
                return $area;
            }
        }

        return null;
    }
}
