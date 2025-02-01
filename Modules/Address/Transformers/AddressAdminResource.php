<?php

namespace Modules\Address\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Modules\APIAuth\Entities\User;
use Modules\Country\Transformers\CityResource;
use Modules\Country\Transformers\CountryResource;

class AddressAdminResource extends Resource
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
            'addressable_type' => $this->addressable_type == User::class ? 'user' : 'outlet',
            'addressable_id' => $this->addressable_id ?? 0,
            'country' => $this->country ? new CountryResource($this->country) : (object)[],
            'city' => $this->city ? new CityResource($this->city) : (object)[],
            'title' => $this->title ?? '',
            'state' => $this->state ?? '',
            'postal_code' => $this->postal_code ?? '',
            'street' => $this->street ?? '',
            'building' => $this->building ?? '',
            'apartment' => $this->apartment ?? '',
            'floor' => $this->flat ?? '',
            'landmark' => $this->landmark ?? '',
            'phone' => $this->phone ?? '',
            'mobile' => $this->mobile ?? '',
            'lat' => $this->lat ?? '',
            'lng' => $this->lng ?? '',
            'location' => $this->lat . ',' . $this->lng ?? '',
            'address_details' => $this->address_details ?? '',
            'is_primary' => (bool) $this->is_primary,
            // 'is_shipping' => (bool) $this->is_shipping,
        ];
    }
}
