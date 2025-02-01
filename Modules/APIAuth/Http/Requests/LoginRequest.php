<?php

namespace Modules\APIAuth\Http\Requests;

use GuzzleHttp\Client;
use Modules\APIAuth\Entities\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Http\FormRequest;
use Modules\APIAuth\Transformers\UserResource;

class LoginRequest extends FormRequest
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
            'username' => 'required|string',
            'password' => 'required|string',
        ];
    }

    public function tryLoginOld()
    {
        $client = new Client([
            'base_uri' => config('app.url'),
            'defaults' => [
                'exceptions' => false
            ],
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
            'timeout' => 3.0,
        ]);
        try {
            $response = $client->post('/oauth/token', [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => config('app.oauth_client_id'),
                    'client_secret' => config('app.oauth_client_secret'),
                    'username' => $this->username,
                    'password' => $this->password,
                ]
            ]);

            $responseContents = json_decode($response->getBody()->getContents());

            $user = User::where('email', $this->username)->first();

            if ($user->email_verified_at == NULL)
            {
                return response()->json(['error' => 'Please Verify Email'], 403);
            }

            return response([
                'token_type' => $responseContents->token_type,
                'expires_in' => $responseContents->expires_in,
                'access_token' => $responseContents->access_token,
                'refresh_token' => $responseContents->refresh_token,
            ]);
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            abort(401, 'Invalid credentials, the username or password is not correct.');
        }
    }

    public function tryLogin()
    {
        $user_username = User::where('username', $this->username)->first();
        $user_email = User::where('email', $this->username)->first();
        $user_phone = User::where('phone', $this->username)->first();
        // dd($user_username, $user_email, $user_phone);

        if($user_username && Hash::check($this->password, $user_username->password))
        {
            if ($user_username->email_verified_at == null && $user_username->phone_verified_at == null)
            {
                return response()->json(['message' => 'Please Verify Your Account Firstly'], 403);
            }
            $token =  $user_username->createToken('my-app')->accessToken;
            return response([
                'message' => 'logged in successfully',
                'access_token' => $token,
                'user' => new UserResource($user_username)
            ]);
        }

        if($user_email && Hash::check($this->password, $user_email->password))
        {
            if ($user_email->email_verified_at == null) {
                return response()->json(['message' => 'Please Verify Your Email Firstly'], 403);
            }
            $token =  $user_email->createToken('my-app')->accessToken;
            return response([
                'message' => 'logged in successfully',
                'access_token' => $token,
                'user' => new UserResource($user_email)
            ]);
        }
        else if ($user_phone && Hash::check($this->password, $user_phone->password))
        {
            if ($user_phone->phone_verified_at == null)
            {
                return response()->json(['message' => 'Please Verify Your Phone Firstly'], 403);
            }
            $token =  $user_phone->createToken('my-app')->accessToken;
            return response([
                'message' => 'logged in successfully',
                'access_token' => $token,
                'user' => new UserResource($user_phone)
            ]);
        }

        return response(['message' => 'Invalid credentials, the username or password is not correct.'], 401);
    }
}
