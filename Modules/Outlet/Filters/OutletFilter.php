<?php

namespace Modules\Outlet\Filters;

use App\Helpers\QueryFilter;
use Modules\Product\Entities\ProductType;

class OutletFilter extends QueryFilter
{
    public function name($name)
    {
        return  $this->builder->whereHas('translations', function($query) use($name){
            $query->where('name', 'like', '%' . $name . '%');
        });
    }

    public function productType($ids)
    {
        $ids = explode(',', $ids);
        $all_leaves_ids = [];
        foreach ($ids as $key => $id) {
            $leaves_ids = ProductType::where('id', $id)->first()->leaves_types;

            $all_leaves_ids = array_unique(array_merge($all_leaves_ids, $leaves_ids));
        }

        return $this->builder->whereHas('products.productType', function ($query) use ($all_leaves_ids) {
            return $query->whereIn('id', $all_leaves_ids);
        });
    }

    public function sort($sort)
    {
        if($sort == 'alpha')
        {
            return $this->builder->join('outlet_translations', 'outlet_translations.outlet_id', '=', 'outlets.id')
                ->orderBy('outlet_translations.name');
        }
    }

    public function working($day_and_time)
    {
        $day_and_time = explode(',', $day_and_time);
        $day = $day_and_time[0];
        $time = $day_and_time[1];

        return $this->builder->whereHas('workingHours', function($query) use($day, $time){
            $query->where('day', 'like', '%' . $day . '%')
                ->where('time_from', '<=', $time)
                ->where('time_to', '>=', $time);
        });
    }
}
