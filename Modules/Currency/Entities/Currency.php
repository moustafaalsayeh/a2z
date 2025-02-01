<?php

namespace Modules\Currency\Entities;

use NumberFormatter;
use App\Helpers\Translatable;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use Translatable;

    protected $fillable = ['code'];

    public $timestamps = false;

    public $translatedAttributes = [
        'title'
    ];

    public function getSymbolAttribute()
    {
        $locale = in_array(request()->header('X-locale'), config('translatable.locales')) ? request()->header('X-locale') : config('translatable.fallback_locale');
        $fmt = new NumberFormatter($locale . "@currency=$this->code", NumberFormatter::CURRENCY);
        return $fmt->getSymbol(NumberFormatter::CURRENCY_SYMBOL);
    }
}
