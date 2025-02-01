<?php

namespace Modules\Order\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class OrderItemSpecResource extends Resource
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
            'spec_title' => $this->spec_title ?? '',
            'answer_string' => $this->answer_string ?? '',
            'answer_price' => $this->answer_price ?? 0,
        ];
    }
}
