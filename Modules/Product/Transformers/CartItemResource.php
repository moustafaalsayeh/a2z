<?php

namespace Modules\Product\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Modules\Currency\Transformers\CurrencyResource;
use Modules\ProductSpecification\Transformers\SpecificableAnswerResource;

class CartItemResource extends Resource
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
            'product' => $this->product ? new ProductSimpleResource($this->product) : (object)[],
            'quantity' => $this->quantity,
            'answers' => $this->answer_string ?? '',
            'item_total_price' => $this->item_total_price,
            // 'specifications_answers' => $this->specificationsAnswers ? SpecificableAnswerResource::collection($this->specificationsAnswers): []
        ];
    }
}
