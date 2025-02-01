<?php

namespace Modules\ProductSpecification\Entities;

use Illuminate\Database\Eloquent\Model;

class ProductSpecificationOptionTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'opt_id',
        'locale',
        'value',
    ];

    public function productSpecificationOption()
    {
        return $this->belongsTo(ProductSpecificationOption::class, 'opt_id');
    }
}
