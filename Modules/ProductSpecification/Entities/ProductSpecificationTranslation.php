<?php

namespace Modules\ProductSpecification\Entities;

use Illuminate\Database\Eloquent\Model;

class ProductSpecificationTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'prod_spec_id',
        'locale',
        'title',
        'hint'
    ];

    public function productSpecification()
    {
        return $this->belongsTo(ProductSpecification::class, 'prod_spec_id');
    }
}
