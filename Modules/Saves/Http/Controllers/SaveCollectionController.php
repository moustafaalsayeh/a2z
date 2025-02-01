<?php

namespace Modules\Saves\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Media\Entities\Media;
use Illuminate\Routing\Controller;
use Modules\APIAuth\Helpers\Helpers;
use Modules\Saves\Entities\SaveCollection;
use Modules\Saves\Transformers\SaveCollectionResounce;

class SaveCollectionController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('api')->user();

        if ($user->type != 'buyer') {
            abort(403);
        }

        return response([
            'message' => __('success_action', ['model' => __('save_collection'), 'action' => __('retrieved')]),
            'data' => SaveCollectionResounce::collection($user->saveCollections)
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
            'name' => 'required|min:2|max:100',
            'photos' => 'sometimes|array',
            'photos.*.image' => 'required_with:photos|image',
            'photos.*.title' => 'sometimes|min:2|max:100',
            'photos.*.description' => 'sometimes|min:2|max:199',
            'photos.*.is_main' => 'sometimes|in:0,1',
        ]);

        $save_collection = $user->saveCollections()->create([
            'name' => $request->name
        ]);

        if ($request->photos) {
            Helpers::addPhotosToModel($save_collection, $request->photos);
        }

        return response([
            'message' => __('success_action', ['model' => __('save_collection'), 'action' => __('added')]),
            'save_collections' => SaveCollectionResounce::collection($user->saveCollections)
        ]);
    }

    public function update(Request $request, SaveCollection $save_collection)
    {
        $user = auth('api')->user();

        if ($user->type != 'buyer') {
            abort(403);
        }

        $request->validate([
            'name' => 'sometimes|min:2|max:100',
            'photos' => 'sometimes|array',
            'photos.*.image' => 'required_with:photos|image',
            'photos.*.title' => 'sometimes|min:2|max:100',
            'photos.*.description' => 'sometimes|min:2|max:199',
            'photos.*.is_main' => 'sometimes|in:0,1',
        ]);

        $save_collection->update([
            'name' => $request->name
        ]);

        if ($request->photos) {
            Helpers::addPhotosToModel($save_collection, $request->photos);
        }

        return response([
            'message' => __('success_action', ['model' => __('save_collection'), 'action' => __('updated')]),
            'save_collection' => new SaveCollectionResounce($save_collection)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy(SaveCollection $save_collection)
    {
        if ($save_collection->user->id == auth('api')->user()->id) {
            $save_collection = $save_collection->delete();

            return response([
                'message' => __('success_action', ['model' => __('save_collection'), 'action' => __('deleted')])
            ]);
        }

        abort(401, 'This collection doesn\'t belong to you');
    }

    public function updateMedia(Request $request, SaveCollection $save_collection, Media $media)
    {
        if ($media->mediable->id == $save_collection->id) {
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
        abort(401, 'This media doesn\'t belong to that collection');
    }

    public function destoryMedia(SaveCollection $save_collection, Media $media)
    {
        if ($media->mediable->id == $save_collection->id) {
            $save_collection->deleteMedia($media);

            return response([
                'message' => __('success_action', ['model' => __('media'), 'action' => __('deleted')])
            ]);
        }
        abort(401, 'This media doesn\'t belong to that collection');
    }
}
