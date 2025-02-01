<?php

namespace Modules\Address\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Modules\Country\Transformers\CityResource;
use Modules\Country\Transformers\CountryResource;

class AddressSimpleResource extends Resource
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
            'country' => $this->country ? new CountryResource($this->country) : (object)[],
            'city' => $this->city ? new CityResource($this->city) : (object)[],
            'lat' => $this->lat ?? '',
            'lng' => $this->lng ?? '',
            'location' => $this->lat . ',' . $this->lng ?? '',
        ];
    }
}
