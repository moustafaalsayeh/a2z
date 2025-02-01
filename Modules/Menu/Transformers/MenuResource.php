<?php

namespace Modules\Menu\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Modules\Product\Transformers\ProductSimpleResource;

class MenuResource extends Resource
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
            'outlet_id' => $this->outlet ? $this->outlet->id : 0,
            'name' => $this->name ? $this->name : '',
            'description' => $this->description ? $this->description : '',
            'products' => $this->products ? ProductSimpleResource::collection($this->products) : (object)[],
        ];
    }
}
