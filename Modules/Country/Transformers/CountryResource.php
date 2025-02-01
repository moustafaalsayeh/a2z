<?php

namespace Modules\Country\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class CountryResource extends Resource
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
            'alpha2code' => $this->alpha2code,
            'name' => $this->name,
            'nativeName' => $this->native_name ?? '',
            'region' => $this->region ?? '',
            'flag' => $this->flag ?? '',
            'currencies' => json_decode($this->currencies),
        ];
    }
}
