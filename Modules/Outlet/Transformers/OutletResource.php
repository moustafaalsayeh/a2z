<?php

namespace Modules\Outlet\Transformers;

use Modules\Product\Entities\Cart;
use Modules\Menu\Transformers\MenuResource;
use Illuminate\Http\Resources\Json\Resource;
use Modules\APIAuth\Transformers\UserSimpleResource;
use Modules\Address\Transformers\AddressSimpleResource;
use Modules\Outlet\Transformers\DeliveryAreaSimpleResource;

class OutletResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $cart = auth('api')->user() ? Cart::where('user_id', auth('api')->user()->id)->where('outlet_id', $this->id)->first() : null;
        return [
            'id' => $this->id,
            'owner_id' => $this->user->id,
            'name' => $this->name,
            'info' => $this->info ? $this->info : '',
            'email' => $this->email ?? '',
            'phone' => $this->phone ?? '',
            'website' => $this->website ? $this->website : '',
            'rank' => $this->rank,
            'available' => $this->available,
            'working_hours' => $this->workingHours,
            'photos' => $this->photos ?? [],
            'menus' => $this->menus ? MenuResource::collection($this->menus) : [],
            'email_verified' => (bool) $this->email_verified_at,
            'phone_verified' => (bool) $this->phone_verified_at,
            'cart_quantity' => $cart ? $cart->cart_quantity : 0,
            'cart_total_price' => $cart ? $cart->cart_total_price : 0,
            'delivery_area_info' =>
                $this->delivery_area_info ?
                new DeliveryAreaSimpleResource($this->delivery_area_info) :
                (object) []
            ,
        ];
    }
}
