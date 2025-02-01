<?php

namespace Modules\Outlet\Entities;

use Illuminate\Database\Eloquent\Model;

class WorkHour extends Model
{
    protected $guarded = ['id'];

    public $timestamps = false;

    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }
}
