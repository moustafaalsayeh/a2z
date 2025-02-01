<?php

namespace Modules\Review\Entities;

use App\Helpers\Filterable;
use App\Helpers\Translatable;
use Illuminate\Database\Eloquent\Model;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductType;

class Reviewable extends Model
{
    use Filterable, Translatable;

    protected $guarded = ['id'];

    public $timestamps = false;

    public $translatedAttributes = ['title'];

    public function product()
    {
        return $this->morphedByMany(Product::class, 'reviewable');
    }

    public function productType()
    {
        return $this->morphedByMany(ProductType::class, 'reviewable');
    }

    public function rates()
    {
        return $this->hasMany(ReviewRate::class, 'reviewable_id');
    }
}
