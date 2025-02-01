<?php

namespace Modules\ProductSpecification\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Outlet\Entities\Outlet;
use Modules\Product\Entities\Product;

class ProdcutSpecificationStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'specificable_type' => 'required|in:product,outlet',
            'specificable_ids' => 'required|array',
            'specificable_ids.*' => [
                'bail',
                'required',
                'exists:'. $this->specificable_type .'s,id',
                function ($attribute, $value, $fail) {
                    if (
                        auth('api')->user()->type != 'admin'
                        && $this->specificable_type == 'product'
                        && Product::find($value)->outlet->user->id != auth('api')->user()->id
                    )
                    {
                        $fail(__('product_doesnt_belong'));
                    }
                    if (
                        auth('api')->user()->type != 'admin'
                        && $this->specificable_type == 'outlet'
                        && Outlet::find($value)->user->id != auth('api')->user()->id
                    )
                    {
                        $fail(__('outlet_doesnt_belong'));
                    }
                },
            ],
            'title' => 'required|min:3|max:255',
            'hint' => 'sometimes|min:3|max:500',
            'type' => 'required|in:text,checkbox,radio',
            'is_required' => 'sometimes|boolean',
            'options' => 'required_if:type,checkbox,radio|array',
            'options.*' => 'required_with:options|array',
            'options.*.value' => 'required_with:options|min:3|max:255',
            'options.*.price' => 'sometimes|numeric',
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

    protected function getValidatorInstance()
    {
        return parent::getValidatorInstance()->after(function ($validator) {
            $this['specificable_type'] = $this->specificable_type == 'product' ? Product::class : Outlet::class;
        });
    }
}
