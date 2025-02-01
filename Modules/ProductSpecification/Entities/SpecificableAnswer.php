<?php

namespace Modules\ProductSpecification\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Product\Entities\CartItem;
use Modules\ProductSpecification\Transformers\ProductSpecificationOptionResource;

class SpecificableAnswer extends Model
{
    protected $guarded = ['id'];

    public $timestamps = false;

    public function specification()
    {
        return $this->belongsTo(ProductSpecification::class, 'prod_spec_id');
    }

    public function cartItem()
    {
        return $this->belongsTo(CartItem::class);
    }

    public function getAnswerPriceAttribute()
    {
        $product_specs_price = 0;

        if ($this->specification->type == 'checkbox')
        {
            foreach ($this->answer as $key => $ans)
            {
                $product_specs_price += $ans && $ans->price ? $ans->price : 0;
            }
        }
        else if ($this->specification->type == 'radio') {
            $product_specs_price += $this->answer && $this->answer->price ? $this->answer->price : 0;
        }

        return round(($product_specs_price), 2);
    }

    public function getAnswerStringAttribute()
    {
        $answers = '';
        if (gettype($this->answer) == 'string')
        {
            $answers .= $this->answer ? $this->answer . ', ' : '';
        }
        else
        {
            foreach ($this->answer as $key2 => $ans) {
                $answers .= $ans && $ans->value ? $ans->value . ', ' : '';
            }
        }
        return $answers ? substr($answers, 0, strlen($answers) - 2) : '';
    }

    public function getAnswerAttribute($value)
    {
        if($this->specification->type == 'checkbox')
        {
            $spec_answer_ids = explode(',', $value);
            $answers_models = ProductSpecificationOption::where('prod_spec_id', $this->specification->id)->whereIn('id', $spec_answer_ids)->get();
            return ProductSpecificationOptionResource::collection($answers_models);
        }
        else if($this->specification->type == 'radio')
        {
            $answers_models = ProductSpecificationOption::where('prod_spec_id', $this->specification->id)->where('id', $value)->first();
            return new ProductSpecificationOptionResource($answers_models);
        }
        return $value;
    }
}
