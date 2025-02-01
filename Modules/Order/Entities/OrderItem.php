<?php

namespace Modules\Order\Entities;

use Modules\Media\Helpers\Mediable;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use Mediable;

    protected $guarded = ['id'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function orderItemSpecs()
    {
        return $this->hasMany(OrderItemSpec::class);
    }

    public function getAnswerStringAttribute()
    {
        $answer_string = '';

        $items_specs = $this->orderItemSpecs ?? [];

        foreach ($items_specs as $key => $spec) {
            $answer_string .= $spec->answer_string . ', ';
        }

        return $answer_string ? substr($answer_string, 0, strlen($answer_string) - 2) : '';
    }

    public function getItemTotalPriceAttribute()
    {
        $answer_price = 0;

        foreach ($this->orderItemSpecs as $key => $spec) {
            $answer_price += $spec->answer_price;
        }

        return round(($this->product_price + $answer_price) * $this->product_quantity, 2);
    }
}
