<?php

namespace Modules\GlobalSetting\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\GlobalSetting\Entities\GlobalSetting;
use Modules\GlobalSetting\Transformers\GlobalSettingResource;

class GlobalSettingController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $this->authorize('viewAny', GlobalSetting::class);

        return response([
            'message' => __('success_action', ['model' => __('global_setting'), 'action' => __('retrieved')]),
            'global_setting' => GlobalSettingResource::collection(GlobalSetting::get())
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', GlobalSetting::class);

        $request->validate([
            'name' => 'required|max:100|unique:global_settings,name',
            'type' => 'required|in:string,numeric,boolean',
            'value' => 'required|' . $request->type
        ]);

        $global_setting = GlobalSetting::create($request->only('name', 'value', 'type'));

        return response([
            'message' => __('success_action', ['model' => __('global_setting'), 'action' => __('added')]),
            'global_setting' => new GlobalSettingResource($global_setting)
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, GlobalSetting $global_setting)
    {
        $this->authorize('update', $global_setting);

        $request->validate([
            'value' => 'sometimes|' . $global_setting->type
        ]);

        $global_setting->update($request->only('name', 'value', 'type'));

        return response([
            'message' => __('success_action', ['model' => __('global_setting'), 'action' => __('updated')]),
            'global_setting' => new GlobalSettingResource($global_setting->fresh())
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy(GlobalSetting $global_setting)
    {
        $this->authorize('delete', $global_setting);

        $global_setting->delete();

        return response([
            'message' => __('success_action', ['model' => __('global_setting'), 'action' => __('deleted')])
        ]);
    }
}
