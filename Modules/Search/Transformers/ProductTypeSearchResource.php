<?php

namespace Modules\Search\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class ProductTypeSearchResource extends Resource
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
            'icon' => $this->main_media ?? (object)[],
        ];
    }
}
