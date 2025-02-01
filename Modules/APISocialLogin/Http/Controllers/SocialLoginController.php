<?php

namespace Modules\APISocialLogin\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\APISocialLogin\Entities\SocialProviderUser;
use Modules\APISocialLogin\Http\Requests\SocialLoginRequest;

class SocialLoginController extends Controller
{
    public function login(SocialLoginRequest $request)
    {
        return response([
            'access_token' =>  $request->login(),
            'message' => __('success_message', ['model' => __('logged_in')]),
        ]);
    }
}
