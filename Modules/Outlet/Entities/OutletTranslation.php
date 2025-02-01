<?php

namespace Modules\Outlet\Entities;

use Illuminate\Database\Eloquent\Model;

class OutletTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'outlet_id',
        'locale',
        'name',
        'info'
    ];

    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }
}
