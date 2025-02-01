<?php

namespace Modules\APIAuth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'first_name' => 'sometimes|string|min:3|max:100',
            'last_name' => 'sometimes|string|min:3|max:100',
            'username' => 'sometimes|string|max:30|unique:users,username',
            'email' => [
                'sometimes',
                'email',
                'unique:users,email,' . auth('api')->user()->id
            ],
            'phone' => [
                'sometimes',
                'unique:users,phone,' . auth('api')->user()->id
            ],
            'gender' => 'sometimes|in:0,1',
            'birthdate' => 'sometimes|date_format:Y-m-d',
            'photos' => 'sometimes|array',
            'photos.*.image' => 'required_with:photos|image',
            'photos.*.title' => 'sometimes|min:2|max:100',
            'photos.*.description' => 'sometimes|min:2|max:199',
            'photos.*.is_main' => 'sometimes|in:0,1',
            'password' => 'sometimes|string|min:6|max:50|confirmed',
            'password_confirmation' => 'sometimes|string|min:6|max:50',
            'currency_id' => 'sometimes|exists:currencies,id',
            'language_id' => 'sometimes|exists:languages,id',
        ];
    }


}
