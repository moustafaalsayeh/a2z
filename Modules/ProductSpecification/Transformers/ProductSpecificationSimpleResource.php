<?php

namespace Modules\ProductSpecification\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Modules\Outlet\Entities\Outlet;
use Modules\Product\Entities\Product;

class ProductSpecificationSimpleResource extends Resource
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
            'title' => $this->title ? $this->title : '',
            'hint' => $this->hint ? $this->hint : '',
            'type' => $this->type ?? '',
            'is_required' => $this->is_required,
            'options' => $this->options ? ProductSpecificationOptionResource::collection($this->options) : []
        ];
    }
}
