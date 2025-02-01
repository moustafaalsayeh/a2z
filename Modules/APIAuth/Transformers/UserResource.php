<?php

namespace Modules\APIAuth\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Modules\Country\Transformers\CityResource;
use Modules\Product\Transformers\CartResource;
use Modules\Address\Transformers\AddressResource;
use Modules\Country\Transformers\CountryResource;
use Modules\Outlet\Transformers\OutletSimpleResource;
use Modules\Saves\Transformers\SaveCollectionResounce;

class UserResource extends Resource
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
            'first_name' => $this->first_name ?? '',
            'last_name' => $this->last_name ?? '',
            'username' => $this->username ?? '',
            'photos' => $this->photos ?? [],
            'type' => $this->type ?? '',
            'email' => $this->email ?? '',
            'phone' => $this->phone ?? '',
            'gender' => $this->gender ?? '',
            'birthdate' => $this->birthdate ?? '',
            'primary_address' => $this->primary_address ? new AddressResource($this->primary_address) : (object)[],
            // 'addresses' => $this->addresses ? AddressResource::collection($this->addresses) : null,
            'language' => $this->language ? $this->language->id: 0,
            'currency' => $this->currency ? $this->currency->id: 0,
            // 'text_fields' => $this->text_fields,
            'email_verified' => (bool) $this->email_verified_at,
            'phone_verified' => (bool) $this->phone_verified_at,
            'outlets' =>  $this->when($this->type == 'outlet', OutletSimpleResource::collection($this->outlets)),
            'roles' =>  $this->when($this->type == 'admin', $this->roles),
            'cart' =>  $this->when($this->type == 'buyer', new CartResource($this->cart)),
            'save_collections' =>  $this->when($this->type == 'buyer' , $this->saveCollections ? SaveCollectionResounce::collection($this->saveCollections) : []),
            'is_blocked' => $this->is_blocked,
        ];
    }
}
