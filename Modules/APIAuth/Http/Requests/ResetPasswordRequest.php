<?php

namespace Modules\APIAuth\Http\Requests;

use Illuminate\Support\Facades\DB;
use Modules\APIAuth\Entities\User;
use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
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
            'token' => 'required|exists:password_resets,token',
            'new_password' => 'required|min:6',
        ];
    }

    public function updateUserPassword()
    {

        $userResetPasswordRaw = DB::table('password_resets')->where('token', $this->token)->first();
        $user = User::where('email', $userResetPasswordRaw->email)->first();

        if (!$user) {
            return false;
        }

        $user->update(['password' => $this->new_password]);
        DB::table('password_resets')->where('token', $this->token)->delete();
        return true;
    }
}
