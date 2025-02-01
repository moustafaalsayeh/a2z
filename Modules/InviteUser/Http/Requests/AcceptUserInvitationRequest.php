<?php

namespace Modules\InviteUser\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AcceptUserInvitationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'token' => 'required|exists:invitations,token',
            'password' => 'required|string|min:6|max:50|confirmed',
            'password_confirmation' => 'required|string|min:6|max:50',
            'first_name' => 'sometimes|min:2|max:100',
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
