<?php

namespace Modules\Saves\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Modules\Product\Entities\Product;
use Modules\Product\Transformers\ProductSimpleResource;
use Modules\Outlet\Entities\Outlet;
use Modules\Outlet\Transformers\OutletSimpleResource;

class SaveCollectionResounce extends Resource
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
            'photos' => $this->photos ?? [],
            // 'items'=> $this->saves ? SavesResounce::collection($this->saves) : null
        ];
    }
}
