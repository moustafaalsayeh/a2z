<?php

namespace Modules\Outlet\Filters;

use App\Helpers\QueryFilter;
use Modules\Product\Entities\ProductType;

class DeliveryAreaFilter extends QueryFilter
{
    public function outlet($outlet_id)
    {
        return  $this->builder->where('outlet_id', $outlet_id);
    }
}
