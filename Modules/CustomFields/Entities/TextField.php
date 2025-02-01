<?php

namespace Modules\CustomFields\Entities;

use Illuminate\Database\Eloquent\Model;

class TextField extends Model
{
    protected $fillable = [
        'textable_name',
        'name',
        'type',
        'placeholder',
        'default'
    ];

    public function textValues()
    {
        return $this->hasMany(Text::class, 'text_field_id');
    }
}
