<?php

namespace Modules\Currency\Entities;

use Illuminate\Database\Eloquent\Model;

class CurrencyTranslation extends Model
{
    protected $fillable = ['title', 'locale', 'currency_id'];

    public $timestamps = false;

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
