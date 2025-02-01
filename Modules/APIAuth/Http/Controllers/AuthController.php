<?php

namespace Modules\APIAuth\Http\Controllers;

use Carbon\Carbon;
use Twilio\Rest\Client;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\APIAuth\Entities\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Modules\APIAuth\Emails\VerifyMail;
use Modules\APIAuth\Emails\ResetPasswordEmail;
use Modules\APIAuth\Helpers\Helpers;
use Modules\APIAuth\Transformers\UserResource;
use Modules\APIAuth\Http\Requests\LoginRequest;
use Modules\APIAuth\Http\Requests\RegisterQueueRequest;
use Modules\APIAuth\Http\Requests\ResetPasswordRequest;
use Modules\APIAuth\Http\Requests\ResetPasswordEmailRequest;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        // if (Auth::attempt(['email' => request('username'), 'password' => request('password')]))
        // {
        //     $user = Auth::user();

        //     if($user->email_verified_at == null && $user->phone_verified_at == null)
        //     {
        //         return response()(['error' => 'Please Verify Your Account Firstly'], 401);
        //     }

        //     $token =  $user->createToken('my-app')->accessToken;
        //     return response([
        //         'message' => 'logged in successfully',
        //         'access_token' => $token,
        //         'user' => new UserResource($user)
        //     ]);
        // }
        // abort(401, 'Invalid credentials, the username or password is not correct.');
        return $request->tryLogin();
    }

    public function registerQueue(RegisterQueueRequest $request)
    {
        $user = $request->registerUser();

        // $access_token = $user->createToken($user->username)->accessToken;

        return response([
            'user' => new UserResource($user),
            'message' => __('success_action', ['model' => __('user'), 'action' => __('registered')]),
            // 'access_token' => $access_token,
        ]);
    }

    public function logout(Request $request)
    {
        $user = auth('api')->user()->token()->delete();

        return response([
            'message' => __('success_message', ['model' => __('logged_out')]),
        ]);
    }

    public function sendResetLinkEmail(ResetPasswordEmailRequest $request)
    {
        $data = $request->userData();

        Mail::to($data['email'])->send(new ResetPasswordEmail($data));

        return response([
            'message' => __('success_action', ['model' => __('password_reset_link'), 'action' => __('sent')]),
        ]);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        if ($request->updateUserPassword()) {
            return response()([
                'message' => __('success_action', ['model' => __('password'), 'action' => __('reset')])
            ]);
        }
        return response()([
            'message' => __('fail_action', ['model' => __('password'), 'action' => __('reset')])
        ], 400);
    }

    public function verifyEmail($email, $verifyToken)
    {
        $user = User::where(['email' => $email, 'email_verify_token' => $verifyToken])->firstOrFail();
        if (null != $user->email_verified_at)
        {
            return response(['message' => __('fail_action', ['model' => __('email'), 'action' => __('verified')])], 400);
        }
        $user->update(['email_verified_at' => Carbon::now()->toDateTimeString()]);
        // $user->notify(new AccountActivatedNotification());
        return response([
            'message' => __('success_action', ['model' => __('email'), 'action' => __('verified')])
        ]);
    }

    public function verifyPhone($phone, $verifyCode)
    {
        $user = User::where('phone', $phone)->firstOrFail();

        if ($user->phone_verify_code == $verifyCode)
        {
            if (null != $user->phone_verified_at)
            {
                return response([
                    'message' => __('fail_action', ['model' => __('phone'), 'action' => __('verified')])
                ], 400);
            }
            $user->update(['phone_verified_at' => Carbon::now()->toDateTimeString()]);
            // $user->notify(new AccountActivatedNotification());
            return response([
                'message' => __('success_action', ['model' => __('phone'), 'action' => __('verified')])
            ]);
        }
        return response(['message' => 'Incorrect Code'], 400);
    }

    public function resendVerifyEmail($email)
    {
        $user = User::where('email', $email)->firstOrFail();
        if ($user) {
            if (null != $user->email_verified_at) {
                return response([
                    'message' => __('already_done_action', ['model' => __('email'), 'action' => __('verified')])
                ], 400);
            }
            $user->email_verify_token = mt_rand(111111, 999999);
            $user->save();
            $user->refresh();
            Helpers::sendVerifyEmail($user->email, $user->email_verify_token);
            return response([
                'message' => __('success_action', ['model' => __('verification_email'), 'action' => __('sent')])
            ]);
        }
        return response([
            'message' => __('fail_action', ['model' => __('verification_email'), 'action' => __('sent')])
        ], 400);
    }

    public function resendVerifyPhone($phone)
    {
        $user = User::where('phone', $phone)->firstOrFail();
        $user->phone_verify_code = mt_rand(111111, 999999);
        $user->save();
        $user->refresh();
        $message = "Verification " . config("app.name") . " mobile\r\n Phone Number: ". $user->phone ."\r\n Verify Code: " . $user->phone_verify_code;

        if (null != $user->phone_verified_at)
        {
            return response([
                'message' => __('already_done_action', ['model' => __('phone'), 'action' => __('verified')])
            ], 400);
        }

        if(Helpers::sendMessage($message, $phone))
        {
            return response([
                'message' => __('success_action', ['model' => __('verification_code'), 'action' => __('sent')])
            ]);
        }
        return response([
            'message' => __('fail_action', ['model' => __('verification_code'), 'action' => __('sent')])
        ], 400);

    }
}
