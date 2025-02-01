<?php

namespace Modules\Currency\Transformers;

use NumberFormatter;
use Illuminate\Http\Resources\Json\Resource;
use Modules\APIAuth\Helpers\Helpers;

class CurrencyResource extends Resource
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
            'code' => $this->code ?? '',
            'title' => $this->title ?? '',
            'symbole' => $this->symbol ?? ''
        ];
    }
}
