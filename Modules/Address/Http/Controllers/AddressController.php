<?php

namespace Modules\Address\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\APIAuth\Entities\User;
use Modules\Outlet\Entities\Outlet;
use Modules\Address\Entities\Address;
use Modules\Address\Filters\AddressFilter;
use Modules\Address\Transformers\AddressResource;
use Modules\Address\Http\Requests\AddressStoreRequest;
use Modules\Address\Transformers\AddressAdminResource;
use Modules\Address\Http\Requests\AddressUpdateRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AddressController extends Controller
{
    use AuthorizesRequests;

    public function indexAdmin(AddressFilter $request)
    {
        $this->authorize('viewAny', Address::class);

        return AddressAdminResource::collection(Address::filter($request)->paginate(20));
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        if($request->exists('outlet'))
        {
            $outlet = Outlet::findOrFail($request->query('outlet'));

            return response([
                'message' => __('success_action', ['model' => __('address'), 'action' => __('retrieved')]),
                'address' => $outlet->address ? new AddressResource($outlet->address) : null
            ]);
        }

        return response([
            'message' => __('success_action', ['model' => __('address'), 'action' => __('retrieved')]),
            'address' => $user->addresses ? AddressResource::collection($user->addresses) : []
        ]);
    }
    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(AddressStoreRequest $request)
    {
        if($request->addressable_type == Outlet::class && Outlet::find($request->addressable_id)->address)
        {
            Outlet::find($request->addressable_id)->address->delete();
        }

        $address = $request->addressable_type::find($request->addressable_id)->addAddress($request->all());

        return response([
            'message' => __('success_action', ['model' => __('address'), 'action' => __('added')]),
            'address' => new AddressResource($address)
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(AddressUpdateRequest $request, Address $address)
    {
        if ($address->addressable == User::class && $address->addressable->id != auth('api')->user()->id) {
            return response(['message' => 'Unauthorized'], 401);
        }
        if ($address->addressable == Outlet::class && $address->addressable->user->id != auth('api')->user()->id) {
            return response(['message' => 'Unauthorized'], 401);
        }
        $addressable = $address->addressable_type::find($address->addressable_id)->editAddress($address, $request->all());

        return response([
            'message' => __('success_action', ['model' => __('address'), 'action' => __('updated')]),
            'address' => new AddressResource($address->fresh())
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy(Address $address)
    {
        if ($address->addressable == User::class && $address->addressable->id != auth('api')->user()->id)
        {
            return response(['message' => 'Unauthorized'], 401);
        }
        if ($address->addressable == Outlet::class && $address->addressable->user->id != auth('api')->user()->id)
        {
            return response(['message' => 'Unauthorized'], 401);
        }

        if($address->is_primary)
        {
            return response(
                ['message' =>  __('fail_action', ['model' => __('primary_address'), 'action' => __('deleted')])],
                401
            );
        }

        $address->delete();

        return response([
            'message' => __('success_action', ['model' => __('address'), 'action' => __('deleted')])
        ]);
    }
}
