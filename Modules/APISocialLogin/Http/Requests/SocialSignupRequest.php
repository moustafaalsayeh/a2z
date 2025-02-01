<?php

namespace Modules\APISocialLogin\Http\Requests;

use Illuminate\Support\Str;
use Modules\APIAuth\Entities\User;
use Illuminate\Foundation\Http\FormRequest;

class SocialSignupRequest extends FormRequest
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
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'nullable|unique:users,phone',
            'provider_id' => 'required|string',
            'provider' => 'required|in:facebook,twitter,google,linkedin',
        ];
    }

    protected function getValidatorInstance()
    {
        $this['provider'] = $this->provider;
        $this['first_name'] = $this->first_name;
        $this['password'] = Str::random(10);
        return parent::getValidatorInstance();
    }

    public function signUp()
    {
        $this->user = $this->getUser();
        $this->makeUserSocialProvider();
        return $this->user->createToken($this->user->first_name)->accessToken;
    }

    public function getUser()
    {
        return User::where('email', $this['email'])
            ->first() ??
            User::create($this->only([
                'first_name',
                'username',
                'email',
                'phone',
                'password'
            ]))->fresh();
    }

    public function makeUserSocialProvider()
    {
        $userSocialProvider = optional($this->user->socialProviders->where('provider_id', $this->provider_id))->first();

        if (!(bool) $userSocialProvider) {
            $userSocialProvider = $this->createSocialProviderUser();
        }
        return $userSocialProvider;
    }

    public function createSocialProviderUser()
    {
        return $this->user
            ->socialProviders()
            ->create($this->only([
                'provider_id',
                'provider'
            ]));
    }
}
