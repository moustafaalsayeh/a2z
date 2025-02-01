<?php

namespace Modules\APISocialLogin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\APISocialLogin\Http\Requests\SocialSignupRequest;

class SocialSignupController extends Controller
{
    public function signup(SocialSignupRequest $request)
    {
        return response([
            'access_token' => $request->signUp(),
            'message' => __('success_message', ['model' => __('signed_up')]),
        ]);
    }
}
