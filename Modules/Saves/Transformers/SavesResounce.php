<?php

namespace Modules\Saves\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Modules\Product\Entities\Product;
use Modules\Product\Transformers\ProductSimpleResource;
use Modules\Outlet\Entities\Outlet;
use Modules\Outlet\Transformers\OutletSimpleResource;

class SavesResounce extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $savable_type = $this->savable_type == Product::class ? 'product' : 'outlet';

        return [
            'id' => $this->id,
            $savable_type => $savable_type == 'product' ? new ProductSimpleResource(Product::findOrFail($this->savable_id)) : new OutletSimpleResource(Outlet::findOrFail($this->savable_id)),
            'collection_name' => $this->saveCollection ? $this->saveCollection->name : null,
            'collection_id' => $this->saveCollection ? $this->saveCollection->id : null,
        ];
    }
}
