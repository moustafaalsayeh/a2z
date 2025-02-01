<?php

namespace Modules\ProductSpecification\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\ProductSpecification\Entities\ProductSpecification;

class ProdcutSpecificationOptionStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'prod_spec_id' => [
                'bail',
                'required',
                'exists:product_specifications,id',
                function ($attribute, $value, $fail) {
                    if (auth('api')->user()->type != 'admin' && ProductSpecification::find($value)->products->first() && ProductSpecification::find($value)->products->first()->outlet->user->id != auth('api')->user()->id) {
                        $fail(__('product_doesnt_belong'));
                    }
                },
                function ($attribute, $value, $fail) {
                    if (ProductSpecification::find($value)->type == 'text') {
                        $fail(__('must_be_of_type', ['user' => __('product_specification_option'), 'model' => 'checkbox or radio']));
                    }
                }
            ],
            'value' => 'required|min:1|max:255',
            'price' => 'sometimes|numeric',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
