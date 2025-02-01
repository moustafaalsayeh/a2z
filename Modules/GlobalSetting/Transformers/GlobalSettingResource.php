<?php

namespace Modules\GlobalSetting\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class GlobalSettingResource extends Resource
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
            'name' => $this->name ?? '',
            'value' => $this->value ?? '',
            'type' => $this->type ?? '',
        ];
    }
}
