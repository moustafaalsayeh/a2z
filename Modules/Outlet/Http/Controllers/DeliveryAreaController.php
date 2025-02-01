<?php

namespace Modules\Outlet\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Outlet\Entities\DeliveryArea;
use Modules\Outlet\Filters\DeliveryAreaFilter;
use Modules\Outlet\Transformers\DeliveryAreaResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Modules\Outlet\Http\Requests\DeliveryAreaStoreRequest;
use Modules\Outlet\Http\Requests\DeliveryAreaUpdateRequest;
use Modules\Outlet\Transformers\DeliveryAreaSimpleResource;

class DeliveryAreaController extends Controller
{
    use AuthorizesRequests;

    public function index(DeliveryAreaFilter $request)
    {
        $this->authorize('viewAny', DeliveryArea::class);

        $delivery_areas = [];

        $user = auth('api')->user();

        if($user->type == 'seller')
        {
            $delivery_areas = DeliveryArea::whereHas('outlet.user', function($query) use($user){
                return $query->where('id', $user->id);
            })->filter($request);
        }
        else
        {
            $delivery_areas = DeliveryArea::filter($request);
        }

        return DeliveryAreaResource::collection($delivery_areas->paginate(20));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(DeliveryAreaStoreRequest $request)
    {
        $this->authorize('create', DeliveryArea::class);

        $delivery_area = DeliveryArea::create($request->only([
            'outlet_id',
            'title',
            'points',
            'delivery_time',
            'delivery_fees',
            'min_order'
        ]));

        return response([
            'message' => __('success_action', ['model' => __('delivery_area'), 'action' => __('stored')]),
            'delivery_area' => new DeliveryAreaResource($delivery_area)
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(DeliveryAreaUpdateRequest $request, DeliveryArea $delivery_area)
    {
        $this->authorize('update', $delivery_area);

        $delivery_area->update($request->only(['title' ,'points', 'delivery_time', 'delivery_fees', 'min_order']));

        return response([
            'message' => __('success_action', ['model' => __('delivery_area'), 'action' => __('updated')]),
            'delivery_area' => new DeliveryAreaResource($delivery_area->fresh())
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy(DeliveryArea $delivery_area)
    {
        $this->authorize('delete', $delivery_area);

        $delivery_area->delete();

        return response([
            'message' => __('success_action', ['model' => __('delivery_area'), 'action' => __('deleted')])
        ]);
    }
}
