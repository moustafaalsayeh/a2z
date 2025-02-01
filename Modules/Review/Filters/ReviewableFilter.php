<?php

namespace Modules\Review\Filters;

use App\Helpers\QueryFilter;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductType;

class ReviewableFilter extends QueryFilter
{
    public function productType($id)
    {
        return $this->builder->where('reviewable_type', ProductType::class)
                        ->where('reviewable_id', $id);
    }

    public function product($id)
    {
        return $this->builder->where('reviewable_type', Product::class)
            ->where('reviewable_id', $id);
    }

    public function sort($sort)
    {
        if ($sort == 'alpha') {
            return $this->builder->join('reviewable_translations', 'reviewable_translations.reviewable_id', '=', 'reviewables.id')
                ->orderBy('reviewable_translations.name');
        }
    }
}
