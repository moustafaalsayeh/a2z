<?php

namespace Modules\Review\Entities;

use Illuminate\Database\Eloquent\Model;

class ReviewableTranslation extends Model
{
    protected $fillable = [
        'revieable_id',
        'locale',
        'title'
    ];

    public $timestamps = false;

    public function reviewable()
    {
        return $this->belongsTo(Reviewable::class);
    }
}
