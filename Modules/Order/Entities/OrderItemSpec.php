<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\ProductSpecification\Entities\ProductSpecification;
use Modules\ProductSpecification\Entities\ProductSpecificationOption;
use Modules\ProductSpecification\Transformers\ProductSpecificationOptionResource;

class OrderItemSpec extends Model
{
    protected $guarded = ['id'];

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function specification()
    {
        return $this->belongsTo(ProductSpecification::class, 'prod_spec_id');
    }

    public function getAnswerAttribute($value)
    {
        if ($this->specification->type == 'checkbox') {
            $spec_answer_ids = explode(',', $value);
            $answers_models = ProductSpecificationOption::where('prod_spec_id', $this->specification->id)->whereIn('id', $spec_answer_ids)->get();
            return ProductSpecificationOptionResource::collection($answers_models);
        } else if ($this->specification->type == 'radio') {
            $answers_models = ProductSpecificationOption::where('prod_spec_id', $this->specification->id)->where('id', $value)->first();
            return new ProductSpecificationOptionResource($answers_models);
        }
        return $value;
    }
}
