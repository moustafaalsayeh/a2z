<?php

namespace Modules\ProductSpecification\Entities;

use App\Helpers\Filterable;
use App\Helpers\Translatable;
use Illuminate\Database\Eloquent\Model;
use Modules\Outlet\Entities\Outlet;
use Modules\Product\Entities\Product;

class ProductSpecification extends Model
{
    use Translatable, Filterable;

    protected $guarded = ['id'];

    public $timestamps = false;

    public $translatedAttributes = [
        'title',
        'hint'
    ];

    public $casts = [
        'is_required' => 'boolean'
    ];

    // public function product()
    // {
    //     return $this->belongsTo(Product::class);
    // }

    public function products()
    {
        return $this->morphedByMany(Product::class, 'specificable');
    }

    public function outlets()
    {
        return $this->morphedByMany(Outlet::class, 'specificable');
    }

    public function translations()
    {
        return $this->hasMany(ProductSpecificationTranslation::class, 'prod_spec_id');
    }

    public function options()
    {
        return $this->hasMany(ProductSpecificationOption::class, 'prod_spec_id');
    }

    public function answers()
    {
        return $this->hasMany(SpecificableAnswer::class, 'prod_spec_id');
    }
}
