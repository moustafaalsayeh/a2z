<?php

namespace Modules\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Product\Entities\ProductType;

class ProductStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'outlet_id' => [
                function ($attribute, $value, $fail) {
                    if(auth('api')->user()->type != 'admin' && !auth('api')->user()->outlets->contains($value))
                    {
                        $fail('this outlet doesn\'t belong to the logged in user');
                    }
                },
                'exists:outlets,id'
            ],
            'name' => 'required|min:2|max:100',
            'description' => 'sometimes|min:2|max:1000',
            'price' => 'required|numeric',
            'photos' => 'sometimes|array',
            'photos.*.image' => 'required_with:photos|image',
            'photos.*.title' => 'sometimes|min:2|max:100',
            'photos.*.description' => 'sometimes|min:2|max:199',
            'photos.*.is_main' => 'sometimes|in:0,1',
            'product_type_id' => [
                'bail',
                'required',
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
