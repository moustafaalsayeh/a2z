<?php

namespace Modules\Outlet\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Media\Entities\Media;
use Illuminate\Routing\Controller;
use Modules\Outlet\Entities\Outlet;
use Modules\APIAuth\Helpers\Helpers;
use Modules\Outlet\Filters\OutletFilter;
use Illuminate\Database\Eloquent\Builder;
use Modules\Outlet\Transformers\OutletResource;
use Modules\Outlet\Http\Requests\OutletStoreRequest;
use Modules\Outlet\Http\Requests\OutletUpdateRequest;
use Modules\Outlet\Transformers\DeliveryAreaResource;
use Modules\Outlet\Transformers\OutletSimpleResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class OutletController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(OutletFilter $request)
    {
        $outlets = Outlet::filter($request)->get();

        if(request()->exists('location'))
        {
            $outlets = Helpers::getOnlyOutletsDelivering($outlets);
        }

        return OutletSimpleResource::collection(Helpers::paginate($outlets, 20));
    }



    public function indexUser(Request $request)
    {
        $outlets = auth('api')->user()->outlets;

        return response([
            'message' => __('success_action', ['model' => __('outlet'), 'action' => __('retrieved')]),
            'outlets' => OutletSimpleResource::collection($outlets)
        ]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show(Outlet $outlet)
    {
        return response([
            'message' => __('success_action', ['model' => __('outlet'), 'action' => __('retrieved')]),
            'outlet' => new OutletResource($outlet)
        ]);
    }

    public function coversLocation(Outlet $outlet, $location)
    {
        $location = array_map('trim', explode(',', $location));

        foreach ($outlet->deliveryAreas as $key => $area) {
            if (Helpers::insidePolygon($location, $area['points'])) {
                return response(["message" => new DeliveryAreaResource($area)]);
            }
        }

        return response(["message" => (object) []]);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(OutletStoreRequest $request)
    {
        $this->authorize('create', Outlet::class);

        $outlet = Outlet::create([
            'user_id' => $request->user_id,
            'email' => $request->email,
            'phone' => $request->phone,
            'website' => $request->website,
            'rank' => $request->rank,
        ]);

        if ($request->photos) {
            Helpers::addPhotosToModel($outlet, $request->photos);
        }

        if ($request->working_hours) {
            $outlet->workingHours()->createMany($request->working_hours);
        }


        if ($outlet->email) {
            $outlet->email_verify_token = mt_rand(111111, 999999);
            // $outlet->email_verified_at = null;
            $outlet->save();

            Helpers::sendVerifyEmail($outlet->email, $outlet->email_verify_token);
        }

        if ($outlet->phone) {
            $outlet->phone_verify_code = mt_rand(111111, 999999);
            // $outlet->phone_verified_at = null;
            $outlet->save();

            Helpers::sendMessage(
                "Verification " . config("app.name") . " mobile\r\n Phone Number: " . $outlet->phone . "\r\n Verify Code: " . $outlet->phone_verify_code,
                (string) $outlet->phone
            );
        }

        return response([
            'message' => __('success_action', ['model' => __('outlet'), 'action' => __('added')]),
            'outlets' => new OutletResource($outlet->fresh())
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(OutletUpdateRequest $request, Outlet $outlet)
    {
        $this->authorize('update', $outlet);

        $outlet->update($request->except(['name', 'info' ,'photos', '_method', 'locale']));

        if ($request->has('photos'))
        {
            Helpers::addPhotosToModel($outlet, $request->photos);
        }

        if ($request->has('email'))
        {
            $outlet->email_verify_token = mt_rand(111111, 999999);
            $outlet->email_verified_at = null;
            $outlet->save();

            Helpers::sendVerifyEmail($outlet->email, $outlet->email_verify_token);
        }

        if ($request->has('phone'))
        {
            $outlet->phone_verify_code = mt_rand(111111, 999999);
            $outlet->phone_verified_at = null;
            $outlet->save();

            Helpers::sendMessage(
                "Verification " . config("app.name") . " mobile\r\n Phone Number: " . $outlet->phone . "\r\n Verify Code: " . $outlet->phone_verify_code,
                (string) $outlet->phone
            );
        }

        return response([
            'message' => __('success_action', ['model' => __('outlet'), 'action' => __('updated')]),
            'outlets' => new OutletResource($outlet->fresh())
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy(Outlet $outlet)
    {
        $this->authorize('delete', $outlet);

        $outlet->delete();

        return response([
            'message' => __('success_action', ['model' => __('outlet'), 'action' => __('deleted')])
        ]);
    }

    public function updateMedia(Request $request, Outlet $outlet, Media $media)
    {
        if ($media->mediable->id == $outlet->id || auth('api')->user()->can('update_outlet_media'))
        {
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
        abort(401, 'This media doesn\'t belong to that outlet');
    }

    public function destoryMedia(Outlet $outlet, Media $media)
    {
        if($media->mediable->id == $outlet->id || auth('api')->user()->can('delete_outlet_media'))
        {
            $outlet->deleteMedia($media);

            return response([
                'message' => __('success_action', ['model' => __('media'), 'action' => __('deleted')])
            ]);
        }
        abort(401, 'This media doesn\'t belong to that outlet');
    }
}
