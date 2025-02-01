<?php

namespace Modules\APIAuth\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Media\Entities\Media;
use Illuminate\Routing\Controller;
use Modules\APIAuth\Helpers\Helpers;
use Modules\APIAuth\Transformers\UserResource;
use Modules\APIAuth\Http\Requests\UpdateUserRequest;
use Modules\APIAuth\Entities\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return response([
            'message' => __('success_action', ['model' => __('user'), 'action' => __('retrieved')]),
            'user' => new UserResource(auth('api')->user())
        ]);
    }

    public function indexAll()
    {
        $auth_user = auth()->user();
        if(!$auth_user->can('show_user'))
        {
            return response(['message' => 'unauthorized'], 401);
        }

        return UserResource::collection(User::paginate(20));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(UpdateUserRequest $request)
    {
        $user = auth('api')->user();
        $user->update($request->except(['password_confirmation', 'photos']));
        // dd($user);

        if ($request->photos) {
            Helpers::addPhotosToModel($user, $request->photos);
        }

        if ($request->email) {
            $user->email_verify_token = mt_rand(111111, 999999);
            $user->email_verified_at = null;
            $user->save();
            $user->refresh();

            Helpers::sendVerifyEmail($user->email, $user->email_verify_token);
        }

        if ($request->phone) {
            $user->phone_verify_code = mt_rand(111111, 999999);
            $user->phone_verified_at = null;
            $user->save();
            $user->refresh();

            Helpers::sendMessage(
                "Verification " . config("app.name") . " mobile\r\n Phone Number: " . $user->phone . "\r\n Verify Code: " . $user->phone_verify_code,
                (string) $user->phone
            );
        }

        return response([
            'message' => __('success_action', ['model' => __('user'), 'action' => __('updated')]),
            'user' => new UserResource($user)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy(User $user)
    {
        $auth_user = auth()->user();
        if(!$auth_user->can('delete_user') || $auth_user->username == 'super')
        {
            return response(['message' => 'unauthorized'], 401);
        }

        $user->delete();

        return response([
            'message' => __('success_action', ['model' => __('user'), 'action' => __('deleted')]),
        ]);
    }

    public function updateMedia(Request $request, Media $media)
    {
        if ($media->mediable->id == auth('api')->user()->id) {
            $request->validate([
                'title' => 'sometimes|min:2|max:100',
                'description' => 'sometimes|min:2|max:199',
                'is_main' => 'sometimes|in:0,1',
            ]);

            $media->update($request->only(['title', 'description', 'is_main']));

            return response([
                'message' => __('success_action', ['model' => __('media'), 'action' => __('updated')]),
                'media' => $media
            ]);
        }
        abort(401, 'This media doesn\'t belong to you');
    }

    public function destoryMedia(Media $media)
    {
        if ($media->mediable->id == auth('api')->user()->id) {
            $media->delete();

            return response([
                'message' => __('success_action', ['model' => __('media'), 'action' => __('deleted')])
            ]);
        }
        abort(401, 'This media doesn\'t belong to you');
    }
}
