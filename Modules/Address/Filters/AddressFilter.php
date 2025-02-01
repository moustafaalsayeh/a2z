<?php

namespace Modules\Address\Filters;

use App\Helpers\QueryFilter;
use Modules\APIAuth\Entities\User;
use Modules\Outlet\Entities\Outlet;

class AddressFilter extends QueryFilter
{
    public function addressable_type($type)
    {
        if($type == 'outlet')
        {
            return  $this->builder->where('addressable_type', Outlet::class);
        }
        if ($type == 'user')
        {
            return  $this->builder->where('addressable_type', User::class);
        }
    }

    public function addressable_id($id)
    {
        return  $this->builder->where('addressable_id', $id);
    }
}
