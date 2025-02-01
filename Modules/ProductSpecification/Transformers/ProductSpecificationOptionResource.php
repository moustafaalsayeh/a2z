<?php

namespace Modules\ProductSpecification\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Modules\Currency\Transformers\CurrencyResource;

class ProductSpecificationOptionResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'value' => $this->value ? $this->value : '',
            'price' => $this->price,
        ];
    }
}
