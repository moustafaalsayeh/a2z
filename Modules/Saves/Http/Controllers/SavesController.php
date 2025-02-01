<?php

namespace Modules\Saves\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Outlet\Entities\Outlet;
use Modules\Product\Entities\Product;
use Modules\Saves\Entities\Save;
use Modules\Saves\Transformers\SaveCollectionResounce;
use Modules\Saves\Transformers\SavesResounce;

class SavesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $user = auth('api')->user();

        if ($user->type != 'buyer') {
            abort(403);
        }

        $response_data = [];

        if($request->query('collection'))
        {
            $saves = $user->saves()->where('save_collection_id', $request->query('collection'))->orderBy('id', 'desc')->get();
            $response_data['saves'] = $saves ? SavesResounce::collection($saves) : [];
        }
        else
        {
            $response_data['saves'] = $user->saves ?
                    SavesResounce::collection($user->saves()->orderBy('id', 'desc')->get()) :
                    [];
            // $response_data['collections'] = $user->saveCollections ?
            //         SaveCollectionResounce::collection($user->saveCollections) :
            //         [];
        }


        return response([
            'message' => __('success_action', ['model' => __('saves'), 'action' => __('retrieved')]),
            'data' => $response_data
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $user = auth('api')->user();

        if ($user->type != 'buyer') {
            abort(403);
        }

        $request->validate([
            'savable_type' => 'required|in:outlet,product|bail',
            'savable_id' => 'required|exists:' . $request->savable_type . 's,id',
            'save_collection_id' => 'sometimes|exists:save_collections,id'
        ]);

        $request['savable_type'] = $request->savable_type == 'product' ? Product::class : Outlet::class;

        if(! $user->saves()->where('savable_type', $request->savable_type)->where('savable_id', $request->savable_id)->first()){
            $save = $user->saves()->create($request->only(['savable_type', 'savable_id', 'save_collection_id']));

            return response([
                'message' => __('success_action', ['model' => __('saves'), 'action' => __('added')]),
                'save' => new SavesResounce($save)
            ]);
        }

        return response([
            'message' => __('already_done_action', ['model' => __('item'), 'action' => __('saved')])
        ]);

    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($save)
    {
        $save = Save::findOrFail($save);
        if ($save->user->id == auth('api')->user()->id) {
            $save = $save->delete();

            return response([
                'message' => __('success_action', ['model' => __('saves'), 'action' => __('deleted')])
            ]);
        }

        abort(401, 'This save doesn\'t belong to you');
    }
}
