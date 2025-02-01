<?php

namespace Modules\CustomFields\Entities;

use Illuminate\Database\Eloquent\Model;

class Text extends Model
{
    protected $guarded = ['id'];

    public function textable()
    {
        return $this->morphTo();
    }

    public function textField()
    {
        return $this->belongsTo(TextField::class);
    }
}
