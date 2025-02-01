<?php

namespace Modules\Product\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Modules\Currency\Transformers\CurrencyResource;
use Modules\Outlet\Transformers\DeliveryAreaResource;

class CartAdminResource extends Resource
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
            'user_id' => $this->user->id,
            'user_name' => $this->user->username,
            'outlet_id' => $this->outlet->id,
            'outlet_name' => $this->outlet->name,
            'items' => $this->items ? CartItemResource::collection($this->items) : [],
            'cart_quantity' => $this->cart_quantity,
            'cart_total_price' => $this->cart_total_price,
            'delivery_area_info' => $this->outlet->delivery_area_info ? new DeliveryAreaResource($this->outlet->delivery_area_info) : (object)[],
            'total_due' => $this->outlet->delivery_area_info ? $this->outlet->delivery_area_info->delivery_fees + $this->cart_total_price : $this->cart_total_price,
        ];
    }
}
