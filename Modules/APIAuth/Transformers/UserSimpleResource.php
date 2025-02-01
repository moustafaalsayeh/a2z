<?php

namespace Modules\APIAuth\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class UserSimpleResource extends Resource
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
            'is_blocked' => $this->is_blocked,
        ];
    }
}
