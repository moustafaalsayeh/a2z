<?php

namespace Modules\Review\Entities;

use Illuminate\Database\Eloquent\Model;

class ReviewRate extends Model
{
    protected $guarded = ['id'];

    public $timestamps = false;

    public function reviewItem()
    {
        return $this->belongsTo(Reviewable::class, 'reviewable_id');
    }

    public function review()
    {
        return $this->belongsTo(Review::class);
    }
}
