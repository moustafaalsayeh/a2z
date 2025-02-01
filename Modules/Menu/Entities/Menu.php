<?php

namespace Modules\Menu\Entities;

use App\Helpers\Filterable;
use App\Helpers\Translatable;
use Modules\Outlet\Entities\Outlet;
use Modules\Product\Entities\Product;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use Translatable, Filterable;

    protected $guarded = ['id'];

    public $timestamps = false;

    public $translatedAttributes = [
        'name',
        'description'
    ];

    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
