<?php

namespace Modules\Menu\Filters;

use App\Helpers\QueryFilter;

class MenuFilter extends QueryFilter
{
    public function name($name)
    {
        return  $this->builder->whereHas('translations', function ($query) use ($name) {
            $query->where('name', 'like', '%' . $name . '%');
        });
    }

    public function outlet($outlet)
    {
        return $this->builder->whereHas('outlet', function ($query) use ($outlet) {
            return $query->where('outlets.id', $outlet);
        });
    }


    public function sort($sort)
    {
        if ($sort == 'alpha') {
            return $this->builder->join('menu_translations', 'menu_translations.menu_id', '=', 'menus.id')
                ->orderBy('menu_translations.name');
        }
    }
}
