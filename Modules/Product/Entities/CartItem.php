<?php

namespace Modules\Product\Entities;

use Modules\APIAuth\Helpers\Helpers;
use Illuminate\Database\Eloquent\Model;
use Modules\ProductSpecification\Entities\SpecificableAnswer;

class CartItem extends Model
{
    protected $fillable = ['product_id', 'cart_id', 'quantity'];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function specificationsAnswers()
    {
        return $this->hasMany(SpecificableAnswer::class);
    }

    public function getItemTotalPriceAttribute()
    {
        // dd($this->product->price);
        $product_price = $this->product->price;

        $product_specs_price = 0;

        foreach ($this->specificationsAnswers as $key => $spec)
        {
            if ($spec->specification->type == 'checkbox') {
                foreach ($spec->answer as $key => $ans) {
                    $product_specs_price += $ans && $ans->price ? $ans->price : 0;
                }
            } else if ($spec->specification->type == 'radio') {
                $product_specs_price += $spec->answer && $spec->answer->price ? $spec->answer->price : 0;
            }
        }

        return round(($product_price + $product_specs_price) * $this->quantity, 2);
    }

    public function getAnswerStringAttribute()
    {
        $answers = '';

        $specs_answers = $this->specificationsAnswers ?? [];

        foreach ($specs_answers as $key => $sepc) {
            if (gettype($sepc->answer) == 'string') {
                $answers .= $sepc->answer ? $sepc->answer . ', ' : '';
            } else {
                foreach ($sepc->answer as $key2 => $ans) {
                    $answers .= $ans && $ans->value ? $ans->value . ', ' : '';
                }
            }
        }
        $answers = $answers ? substr($answers, 0, strlen($answers) - 2) : '';

        return $answers;
    }

    public function getCurrencyAttribute()
    {
        $hepers = new Helpers();
        return $hepers->getCurrency();
    }
}
