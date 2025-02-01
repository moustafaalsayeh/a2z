<?php

namespace Modules\Review\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class ReviewRateResource extends Resource
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
            'title' => $this->reviewItem->title ?? '',
            'rate' => $this->rate,
        ];
    }
}
