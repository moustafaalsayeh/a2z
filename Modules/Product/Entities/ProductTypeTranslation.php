<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;

class ProductTypeTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'product_type_id',
        'locale',
        'name',
        'description'
    ];

    public function productType()
    {
        return $this->belongsTo(ProductType::class);
    }
}
