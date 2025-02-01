<?php

namespace Modules\Payment\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class PaymentMethodResource extends Resource
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
            'brand' => $this->card->brand,
            'card_last_four' => $this->card->last4,
            'card_exp_month' => $this->card->exp_month,
            'card_exp_year' => $this->card->exp_year,
        ];
    }
}
