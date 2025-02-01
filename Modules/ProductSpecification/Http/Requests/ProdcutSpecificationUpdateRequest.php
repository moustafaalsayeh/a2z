<?php

namespace Modules\ProductSpecification\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Product\Entities\Product;

class ProdcutSpecificationUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'sometimes|min:3|max:255',
            'hint' => 'sometimes|min:3|max:500',
            'is_required' => 'sometimes|boolean',
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
