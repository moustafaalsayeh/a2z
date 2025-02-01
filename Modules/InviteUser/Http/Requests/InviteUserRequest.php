<?php

namespace Modules\InviteUser\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InviteUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|unique:users,email|unique:invitations,email',
            'type' => 'required|in:admin,buyer,seller',
            'first_name' => 'required|min:2|max:100',
            'last_name' => 'sometimes|min:2|max:100',
            'username' => 'sometimes|min:2|max:100|unique:users,username',
            'gender' => 'sometimes|numeric|in:0,1',
            'birthdate' => 'sometimes|date_foramt:d-m-Y',
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
