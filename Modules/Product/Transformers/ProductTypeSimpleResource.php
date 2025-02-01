<?php

namespace Modules\Product\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class ProductTypeSimpleResource extends Resource
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
            'parent_product_type_id' => $this->parent ? $this->parent->id : 0,
            'parent_product_type_name' => $this->parent ? $this->parent->name : '',
            'name' => $this->name,
            'description' => $this->description ? $this->description : '',
            'position' => $this->position,
            'photos' => $this->photos ?? [],
            'level' => $this->level,
            'all_children_count' => $this->all_children_count,
        ];
    }
}
