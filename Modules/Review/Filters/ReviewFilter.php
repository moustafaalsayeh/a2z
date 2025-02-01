<?php

namespace Modules\Review\Filters;

use App\Helpers\QueryFilter;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductType;

class ReviewFilter extends QueryFilter
{
    public function product($id)
    {
        return $this->builder->where('product_id', $id);
    }
}
