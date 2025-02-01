<?php

namespace Modules\Product\Transformers;

use Modules\APIAuth\Helpers\Helpers;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Currency\Transformers\CurrencyResource;

class ProductSimpleResource extends Resource
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
            'name' => $this->name,
            'description' => $this->description ? $this->description : '',
            'outlet_id' => $this->outlet->id,
            'outlet_name' => $this->outlet->name,
            'product_type_id' => $this->productType->id,
            'product_type_name' => $this->productType->name,
            'price' => $this->price,
            'photos' => $this->photos ?? [],
            'rank' => $this->rank,
            'is_saved' => $this->is_saved,
        ];
    }
}
