<?php

namespace Modules\Product\Filters;

use App\Helpers\QueryFilter;

class CartFilter extends QueryFilter
{
    public function outlet($id)
    {
        return  $this->builder->where('outlet_id', $id);
    }

    public function user($id)
    {
        return  $this->builder->where('user_id', $id);
    }

}
