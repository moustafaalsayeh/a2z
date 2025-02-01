<?php

namespace Modules\Product\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Modules\Outlet\Transformers\OutletResource;
use Modules\Currency\Transformers\CurrencyResource;
use Modules\Outlet\Transformers\OutletSimpleResource;
use Modules\ProductSpecification\Transformers\ProductSpecificationSimpleResource;

class ProductResource extends Resource
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
            'outlet' => new OutletSimpleResource($this->outlet),
            'product_type' => new ProductTypeSimpleResource($this->productType),
            'name' => $this->name,
            'description' => $this->description ? $this->description : '',
            'price' => $this->price,
            'photos' => $this->photos ?? [],
            'rank' => $this->rank,
            'product_specifications' => $this->productSpecifications ? ProductSpecificationSimpleResource::collection($this->productSpecifications) : [],
            'outlet_specifications' => $this->outlet->productSpecifications ? ProductSpecificationSimpleResource::collection($this->outlet->productSpecifications) : [],
            'is_saved' => $this->is_saved,
        ];
    }
}
