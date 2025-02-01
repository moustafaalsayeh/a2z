<?php

namespace Modules\Search\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class OutletSearchResource extends Resource
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
            'info' => $this->info ?? '',
            'email' => $this->email ?? '',
            'phone' => $this->phone ?? '',
            'website' => $this->website ?? '',
            'photos' => $this->photos ?? [],
        ];
    }
}
