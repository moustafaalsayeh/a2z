<?php

namespace Modules\Product\Entities;

use App\Helpers\Filterable;
use App\Helpers\Translatable;
use Modules\Media\Helpers\Mediable;
use Modules\Outlet\Entities\Outlet;
use Illuminate\Database\Eloquent\Model;
use Modules\APIAuth\Helpers\Helpers;
use Modules\Cart\Entities\Cart;
use Modules\Menu\Entities\Menu;
use Modules\ProductSpecification\Entities\ProductSpecification;
use Modules\Review\Entities\Review;
use Modules\Review\Entities\Reviewable;
use Modules\Saves\Entities\Save;

class Product extends Model
{
    use Mediable, Translatable, Filterable;

    protected $guarded = ['id'];

    public $timestamps = false;

    public $translatedAttributes = [
        'name',
        'description'
    ];

    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }

    public function productType()
    {
        return $this->belongsTo(ProductType::class);
    }

    public function menu()
    {
        return $this->belongsToMany(Menu::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function reviewItems()
    {
        return  $this->morphToMany(Reviewable::class, 'reviewable');
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function saves()
    {
        return $this->morphMany(Save::class, 'savable');
    }

    public function productSpecifications()
    {
        return $this->morphToMany(ProductSpecification::class, 'specificable');
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($product) {
            $product->deleteAllMedia();
            $product->saves()->delete();
            $product->productSpecifications()->delete();
        });
    }

    // [Accessors & Mutators]
    public function getPriceAttribute($value)
    {
        $hepers = new Helpers();
        return $hepers->moneyGetter($value);
    }

    public function getCurrencyAttribute()
    {
        $hepers = new Helpers();
        return $hepers->getCurrency();
    }

    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = Helpers::moneySetter($value);
    }

    public function getRankAttribute()
    {
        $reviews = $this->reviews;
        if ($reviews->count() > 0) {
            $rank = 0;
            foreach ($reviews as $key => $review) {
                $rank += $review->rank;
            }
            return bcdiv($rank, $reviews->count(), 1);
        }

        return null;
    }

    public function getIsSavedAttribute()
    {
        if(auth('api')->user() && $save = auth('api')->user()->saves()->where('savable_type', Product::class)->where('savable_id', $this->id)->first())
        {
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
}
