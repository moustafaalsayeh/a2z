<?php

namespace Modules\Country\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class CityResource extends Resource
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
            'coutnry_id' => $this->country->id,
            'coutnry_name' => $this->country->name,
            'name' => $this->name,
            'timezone' => $this->timezone,
            'name_translation' => $this->name_translation ?? ''
        ];
    }
}
