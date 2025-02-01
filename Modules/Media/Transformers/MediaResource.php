<?php

namespace Modules\Media\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class MediaResource extends Resource
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
            'path' => $this->path ?? '',
            'thumb' => $this->thumb ?? '',
            'meduim' => $this->meduim ?? '',
            'large' => $this->large ?? '',
            'title' => $this->title ?? '',
            'type' => $this->type ?? '',
        ];
    }
}
