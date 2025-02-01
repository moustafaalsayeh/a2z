<?php

namespace Modules\Order\Filters;

use App\Helpers\QueryFilter;

class OrderFilter extends QueryFilter
{
    public function outlet($id)
    {
        return  $this->builder->where('outlet_id', $id);
    }

    public function user($id)
    {
        return  $this->builder->where('user_id', $id);
    }

    public function status($status)
    {
        return  $this->builder->where('status', 'like', '%' . $status . '%');
    }

}
