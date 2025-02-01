<?php

namespace Modules\ProductSpecification\Entities;

use App\Helpers\Translatable;
use Modules\APIAuth\Helpers\Helpers;
use Illuminate\Database\Eloquent\Model;

class ProductSpecificationOption extends Model
{
    use Translatable;

    protected $guarded = ['id'];

    public $timestamps = false;

    public $translatedAttributes = [
        'value'
    ];

    public function productSpecification()
    {
        return $this->belongsTo(ProductSpecification::class, 'prod_spec_id');
    }

    public function translations()
    {
        return $this->hasMany(ProductSpecificationOptionTranslation::class, 'opt_id');
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
}
