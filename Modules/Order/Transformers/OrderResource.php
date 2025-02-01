<?php

namespace Modules\Order\Transformers;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Address\Transformers\AddressResource;
use Modules\APIAuth\Transformers\UserSimpleResource;
use Modules\Product\Transformers\CartItemResource;
use Modules\Currency\Transformers\CurrencyResource;

class OrderResource extends Resource
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
            'outlet_id' => $this->outlet ? $this->outlet->id : 0,
            'outlet_name' => $this->outlet ? $this->outlet->name : '',
            'outlet_main_image' => $this->outlet ? $this->outlet->main_media : '',
            'user_id' => $this->user ? $this->user->id : 0,
            'user_name' => $this->user ? $this->user->username : '',
            'delivery_man' => $this->deliveryMan ? new UserSimpleResource($this->deliveryMan) : (object)[],
            'payment_method' => $this->payment_method,
            'shipping_address' => $this->address ? new AddressResource($this->address) : (object)[],
            'status' => $this->status,
            'prepration_time_minutes' => $this->when($this->prepration_time_minutes, $this->prepration_time_minutes),
            'prepration_time_days' => $this->when($this->prepration_time_days, $this->prepration_time_days),
            'delivery_time' => $this->delivery_time,
            'delivery_fees' => $this->delivery_fees,
            'items' => $this->items ? OrderItemResource::collection($this->items) : (object)[],
            'order_quantity' => $this->order_quantity,
            'order_total_price' => $this->order_total_price,
            'delivery_fees' => $this->outlet && $this->outlet->delivery_area_info ? $this->outlet->delivery_area_info->delivery_fees : 0,
            'total_due' => $this->outlet && $this->outlet->delivery_area_info ? $this->outlet->delivery_area_info->delivery_fees + $this->order_total_price : $this->order_total_price,
            'created_at' => $this->created_at ?? "",
            'accepted_at' => $this->accepted_at ?? "",
            'delivering_at' => $this->delivering_at ?? "",
            'completed_at' => $this->completed_at ?? "",
            'remaining_seconds_for_cancel' => $this->seconds_left_for_cancel
        ];
    }
}
