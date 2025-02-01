<?php

namespace Modules\CustomFields\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTextFieldRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'sometimes|min:3|max:100',
            'type' => [
                'sometimes',
                Rule::in([
                    'text',
                    'email',
                    'password',
                    'number',
                    'date',
                    'time',
                    'datetime',
                    'url',
                    'phone'
                ])
            ],
            'default' => 'sometimes|min:3|max:100',
            'placeholder' => 'sometimes|min:3|max:100',
        ];
    }

}
