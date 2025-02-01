<?php

namespace Modules\APISocialLogin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\APISocialLogin\Entities\SocialProviderUser;

class SocialLoginRequest extends FormRequest
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
            'provider' => 'required|in:facebook,twitter,google,linkedin',
            'provider_id' => 'required|exists:social_provider_users,provider_id',
        ];
    }

    public function getValidatorInstance()
    {
        $this['provider'] = $this->provider;
        return parent::getValidatorInstance();
    }

    public function login()
    {
        $user = SocialProviderUser::where('provider_id', $this['provider_id'])->first()->user;
        return $user->createToken($user->first_name)->accessToken;
    }
}
