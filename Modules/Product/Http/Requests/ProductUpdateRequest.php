<?php

namespace Modules\Product\Http\Requests;

use Illuminate\Validation\Rule;
use Modules\Product\Entities\ProductType;
use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'sometimes|min:2|max:100',
            'description' => 'sometimes|min:2|max:1000',
            'price' => 'sometimes|numeric',
            'photos' => 'sometimes|array',
            'photos.*.image' => 'required_with:photos|image',
            'photos.*.title' => 'sometimes|min:2|max:100',
            'photos.*.description' => 'sometimes|min:2|max:199',
            'photos.*.is_main' => 'sometimes|in:0,1',
            'product_type_id' =>[
                'bail',
                'sometimes',
                'exists:product_types,id',
                function ($attribute, $value, $fail)
                {
                    if(ProductType::where('id', $value)->first()->children->count())
                    {
                        $fail(__('product_type_not_leaf'));
                    }
                }
            ]
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
