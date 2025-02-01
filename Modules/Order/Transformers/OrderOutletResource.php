<?php

namespace Modules\Order\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Modules\Address\Transformers\AddressResource;
use Modules\Product\Transformers\CartItemResource;
use Modules\Currency\Transformers\CurrencyResource;
use Modules\APIAuth\Transformers\UserSimpleResource;

class OrderOutletResource extends Resource
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
            'order_id' => $this->id + 1000000000,
            'outlet_id' => $this->outlet->id,
            'outlet_name' => $this->outlet->name ?? '',
            'outlet_logo' => $this->outlet->logo_media ?? '',
            'user_id' => $this->user ? $this->user->id : 0,
            'user_name' => $this->user ? $this->user->username : '',
            'delivery_man' => $this->deliveryMan ? new UserSimpleResource($this->deliveryMan) : (object)[],
            'shipping_address' => $this->address ? new AddressResource($this->address) : (object)[],
            'payment_method' => $this->payment_method,
            'status' => $this->status,
            'prepration_time_minutes' => $this->when($this->prepration_time_minutes, $this->prepration_time_minutes),
            'prepration_time_days' => $this->when($this->prepration_time_days, $this->prepration_time_days),
            'items' => $this->items ? OrderItemResource::collection($this->items) : (object)[],
            'order_quantity' => $this->order_quantity,
            'order_total_price' => $this->order_total_price,
            'created_at' => $this->created_at ?? "",
            'accepted_at' => $this->accepted_at ?? "",
            'delivering_at' => $this->delivering_at ?? "",
            'completed_at' => $this->completed_at ?? "",
        ];
    }
}
