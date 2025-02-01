<?php

namespace Modules\Outlet\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Modules\Outlet\Transformers\DeliveryAreaSimpleResource;

class OutletSimpleResource extends Resource
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
            'name' => $this->name,
            'info' => $this->info ? $this->info : '',
            'email' => $this->email ?? '',
            'phone' => $this->phone ?? '',
            'website' => $this->website ? $this->website : '',
            'rank' => $this->rank,
            'available' => $this->available,
            'working_hours' => $this->workingHours,
            'photos' => $this->photos ?? [],
            'is_saved' => $this->is_saved,
            'delivery_area_info' =>
                $this->delivery_area_info ?
                new DeliveryAreaSimpleResource($this->delivery_area_info) :
                (object) []
            ,
        ];
    }
}
