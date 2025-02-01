<?php

namespace Modules\Search\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class ProductSearchResource extends Resource
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
            'name' => $this->name ?? '',
            'description' => $this->description ?? '',
            'outlet_id' => $this->outlet->id,
            'outlet_name' => $this->outlet->name,
            'product_type_id' => $this->productType->id,
            'product_type_name' => $this->productType->name,
            'price' => $this->price,
            'photos' => $this->photos ?? [],
            'rank' => $this->rank,
        ];
    }
}
