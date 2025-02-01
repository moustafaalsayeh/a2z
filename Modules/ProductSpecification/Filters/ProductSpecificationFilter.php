<?php

namespace Modules\ProductSpecification\Filters;

use App\Helpers\QueryFilter;
use Modules\Product\Entities\ProductType;

class ProductSpecificationFilter extends QueryFilter
{
    public function product($product)
    {
        return $this->builder->whereHas('product', function ($query) use ($product) {
            return $query->where('products.id', $product);
        });
    }

}
