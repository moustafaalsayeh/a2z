<?php

namespace Modules\ProductSpecification\Transformers;

use Modules\Outlet\Entities\Outlet;
use Modules\Product\Entities\Product;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Outlet\Transformers\OutletSimpleResource;
use Modules\Product\Transformers\ProductSimpleResource;

class ProductSpecificationResource extends Resource
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
            'products' => $this->products ? ProductSimpleResource::collection($this->products) : (object)[],
            'outlets' => $this->outlets ? OutletSimpleResource::collection($this->outlets) : (object)[],
            'title' => $this->title ? $this->title : '',
            'hint' => $this->hint ? $this->hint : '',
            'type' => $this->type ?? '',
            'is_required' => $this->is_required,
            'options' => $this->options ? ProductSpecificationOptionResource::collection($this->options) : []
        ];
    }
}
