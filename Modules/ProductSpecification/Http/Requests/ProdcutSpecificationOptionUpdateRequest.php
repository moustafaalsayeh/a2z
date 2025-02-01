<?php

namespace Modules\ProductSpecification\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\ProductSpecification\Entities\ProductSpecification;

class ProdcutSpecificationOptionUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'value' => 'sometimes|min:3|max:255',
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
