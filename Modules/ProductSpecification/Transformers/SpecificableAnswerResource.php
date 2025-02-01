<?php

namespace Modules\ProductSpecification\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class SpecificableAnswerResource extends Resource
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
            'specification' => new ProductSpecificationResource($this->specification) ?? (object)[],
            'answer' => $this->answer ? $this->answer : '',
        ];
    }
}
