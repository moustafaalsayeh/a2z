<?php

namespace Modules\APIAuth\Http\Requests;

use Twilio\Rest\Client;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Modules\APIAuth\Entities\User;
use Illuminate\Support\Facades\Mail;
use Modules\APIAuth\Helpers\Helpers;
use Modules\APIAuth\Emails\VerifyMail;
use Illuminate\Foundation\Http\FormRequest;
use Modules\APIAuth\Jobs\SendVerifyEmailJob;

class RegisterQueueRequest extends FormRequest
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
            'first_name' => 'required|string|min:3|max:100',
            'last_name' => 'required|string|min:3|max:100',
            'username' => 'required|string|max:30|unique:users,username',
            'email' => [
                Rule::requiredIf(!$this->phone),
                'email',
                'unique:users,email'
            ],
            'phone' => [
                Rule::requiredIf(!$this->email),
                'unique:users,phone'
            ],
            'gender' => 'sometimes|in:0,1',
            'birthdate' => 'sometimes|date_format:Y-m-d',
            'photos' => 'sometimes|array',
            'photos.*.image' => 'required_with:photos|image',
            'photos.*.title' => 'sometimes|min:2|max:100',
            'photos.*.description' => 'sometimes|min:2|max:199',
            'photos.*.is_main' => 'sometimes|in:0,1',
            'password' => 'required|string|min:6|max:50|confirmed',
            'password_confirmation' => 'required|string|min:6|max:50',
            'type' => 'required|in:buyer,admin,seller,delivery',
            'currency_id' => 'sometimes|exists:currencies,id',
            'language_id' => 'sometimes|exists:languages,id',
        ];
    }

    public function getValidatorInstance()
    {
        $this['email_verify_token'] = mt_rand(111111, 999999);
        $this['phone_verify_code'] = mt_rand(111111, 999999);
        return parent::getValidatorInstance();
    }

    public function registerUser()
    {
        $user = User::create($this->except(['email_verified_at', 'phone_verified_at', 'password_confirmation', 'photos']));

        if ($this->email) {
            Helpers::sendVerifyEmail($user->email, $user->email_verify_token);
        }

        if ($this->phone) {
            Helpers::sendMessage(
                "Verification " . config("app.name") . " mobile\r\n Phone Number: " . $this->phone . "\r\n Verify Code: " . $this['phone_verify_code'],
                (string) $user->phone
            );
        }

        if ($this->photos) {
            Helpers::addPhotosToModel($user, $this->photos);
        }

        return $user;
    }
}
