<?php

namespace Modules\Product\Filters;

use App\Helpers\QueryFilter;
use Modules\Product\Entities\ProductType;

class ProductFilter extends QueryFilter
{
    public function name($name)
    {
        return  $this->builder->whereHas('translations', function ($query) use ($name) {
            $query->where('name', 'like', '%' . $name . '%')
            ->orWhere('description', 'like', '%' . $name . '%');
        });
    }

    public function outlet($outlet)
    {
        return $this->builder->whereHas('outlet', function ($query) use ($outlet) {
            return $query->where('outlets.id', $outlet);
        });
    }

    public function menu($menu)
    {
        return $this->builder->whereHas('menu', function ($query) use ($menu) {
            return $query->where('menus.id', $menu);
        });
    }

    public function productType($ids)
    {
        $ids = explode(',', $ids);
        $all_leaves_ids = [];
        foreach ($ids as $key => $id)
        {
            $leaves_ids = ProductType::where('id', $id)->first()->leaves_types;

            $all_leaves_ids = array_unique(array_merge($all_leaves_ids, $leaves_ids));
        }

        return $this->builder->whereHas('productType', function ($query) use ($all_leaves_ids) {
            return $query->whereIn('id', $all_leaves_ids);
        });
    }

    public function sort($sort)
    {
        if ($sort == 'alpha') {
            return $this->builder->join('product_translations', 'product_translations.product_id', '=', 'products.id')
                ->orderBy('product_translations.name');
        }
    }

}
