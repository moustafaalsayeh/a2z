<?php

namespace Modules\Menu\Entities;

use Illuminate\Database\Eloquent\Model;

class MenuTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'menu_id',
        'locale',
        'name',
        'description'
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
