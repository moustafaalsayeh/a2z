<?php

namespace Modules\InviteUser\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Mail\SendInvitationMail;
use Illuminate\Routing\Controller;
use Modules\APIAuth\Entities\User;
use Illuminate\Support\Facades\Mail;
use Modules\InviteUser\Emails\VerifyMail;
use Modules\InviteUser\Jobs\InviteUserJob;
use Modules\InviteUser\Entities\Invitation;
use Modules\InviteUser\Emails\InviteUserMail;
use Modules\APIAuth\Transformers\UserResource;
use Modules\APIAuth\Emails\VerifyMail as APVerifyMAil;
use Modules\InviteUser\Http\Requests\InviteUserRequest;
use Modules\APIAuth\Emails\InviteUserMail as APInviteUser;
use Modules\InviteUser\Http\Requests\AcceptUserInvitationRequest;

class InviteUserController extends Controller
{

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function create(InviteUserRequest $request)
    {
        if(!auth('api')->user()->can('invite_users'))
        {
            return response(['message' => 'Unauthenticated.'],401);
        }
        $request['token'] = Str::random(15);
        $user_invitation = Invitation::create($request->all());

        // dispatch(new InviteUserJob($user_invitation));
        $url = config('app.url') . url('accept-invitation') . '/' . $user_invitation->token;

        Mail::to($user_invitation->email)->send(new SendInvitationMail($url));

        return response([
            'message' => __('success_action', ['model' => __('invitation'), 'action' => __('sent')])
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function accept(AcceptUserInvitationRequest $request)
    {
        $user_invitation = Invitation::where('token', $request->token)->firstOrFail();

        $user = User::create([
            'email' => $user_invitation->email,
            'email_verified_at' => now(),
            'type' => $user_invitation->type,
            'password' => $request->password,
            'username' => $request->username ?? $user_invitation->username,
            'first_name' => $request->first_name ?? $user_invitation->first_name,
            'last_name' => $request->last_name ?? $user_invitation->last_name,
            'gender' => $request->gender ?? $user_invitation->gender,
            'birthdate' => $request->birthdate ?? $user_invitation->birthdate,
        ]);

        $user_invitation->delete();

        $token =  $user->createToken('my-app')->accessToken;
        return response([
            'message' => 'Account created successfully',
            'access_token' => $token,
            'user' => new UserResource($user)
        ]);

    }
}
