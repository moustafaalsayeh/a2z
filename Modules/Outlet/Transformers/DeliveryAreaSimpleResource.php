<?php

namespace Modules\Outlet\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class DeliveryAreaSimpleResource extends Resource
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
            'title' => $this->title,
            'delivery_time' => $this->delivery_time,
            'delivery_fees' => $this->delivery_fees,
            'min_order' => $this->min_order,
        ];
    }
}
