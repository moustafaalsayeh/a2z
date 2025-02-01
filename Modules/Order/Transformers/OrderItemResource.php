<?php

namespace Modules\Order\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class OrderItemResource extends Resource
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
            'product_name' => $this->product_name ?? '',
            'product_image' => $this->photo ?? '',
            'product_price' => $this->product_price,
            'product_quantity' => $this->product_quantity ?? 0,
            'answers' => $this->answer_string ?? '',
            'item_total_price' => $this->item_total_price,
            // 'specifications_answers' => $this->specificationsAnswers ? SpecificableAnswerResource::collection($this->specificationsAnswers): []
        ];
    }
}
