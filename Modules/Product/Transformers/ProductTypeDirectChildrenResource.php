<?php

namespace Modules\Product\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Modules\Product\Transformers\ProductTypeSimpleResource;

class ProductTypeDirectChildrenResource extends Resource
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
            'position' => $this->position,
            'photos' => $this->photos ?? [],
            'parent_id' => $this->product_type_id,
            'direct_children_count' => $this->direct_children_count,
            'all_children_count' => $this->all_children_count,
            'all_children' => $this->children ? ProductTypeSimpleResource::collection($this->children) : []
        ];
    }
}
