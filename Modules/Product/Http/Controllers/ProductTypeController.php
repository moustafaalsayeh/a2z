<?php

namespace Modules\Product\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Media\Entities\Media;
use Illuminate\Routing\Controller;
use Modules\Product\Entities\ProductType;
use Modules\Product\Transformers\ProductTypeResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Modules\APIAuth\Helpers\Helpers;
use Modules\Outlet\Entities\Outlet;
use Modules\Product\Transformers\ProductTypeSimpleResource;
use Modules\Product\Transformers\ProductTypeDirectChildrenResource;

class ProductTypeController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $product_types = ProductType::where('product_type_id', null)->get();

        if (request()->exists('location')) {
            $delivering_outlets = Helpers::getOnlyOutletsDelivering(Outlet::get());
            $root_delivering_product_types = [];

            foreach ($delivering_outlets as $index => $outlet) {
                $delivering_outlets_products = $outlet->products;
                $delivering_product_types = [];
                foreach ($delivering_outlets_products as $key => $product) {
                    if (!in_array($product->productType, $delivering_product_types)) {
                        $delivering_product_types[] = $product->productType;
                    }
                }

                foreach ($delivering_product_types as $key => $product_type) {
                    // dd($product_type->rootParent());
                    $root_parent = $product_type->rootParent();
                    if (!in_array($root_parent, $root_delivering_product_types)) {
                        $root_delivering_product_types[] = $root_parent;
                    }
                }
            }

            $product_types = $root_delivering_product_types;
        }

        return response(ProductTypeResource::collection($product_types));
    }

    public function list()
    {
        $product_types = ProductType::get();

        if (request()->exists('location')) {
            $product_types = $this->getOnlyProductTypesDelivering($product_types);
        }

        return response(ProductTypeSimpleResource::collection($product_types));
    }

    public function show(ProductType $product_type)
    {
        $product_type_root_parent = $product_type->rootParent();
        return response([
            'message' => __('success_action', ['model' => __('product_type'), 'action' => __('retreived')]),
            'product_type' => new ProductTypeDirectChildrenResource($product_type),
            'product_type_root' =>
                $product_type->id == $product_type_root_parent->id ? (object)[] : new ProductTypeDirectChildrenResource($product_type->rootParent()),
        ]);
    }
    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', ProductType::class);

        $request->validate([
            'name' => 'required|min:2|max:100',
            'description' => 'sometimes|min:2|max:1000',
            'photos' => 'sometimes|array',
            'photos.*.image' => 'required_with:photos|image',
            'photos.*.title' => 'sometimes|min:2|max:100',
            'photos.*.description' => 'sometimes|min:2|max:199',
            'photos.*.is_main' => 'sometimes|in:0,1',
            'position' => 'sometimes|numeric',
            'product_type_id' => 'sometimes|exists:product_types,id'
        ]);

        $product_type = ProductType::create($request->only(['position', 'product_type_id']));

        if ($request->photos)
        {
            Helpers::addPhotosToModel($product_type, $request->photos);
        }

        return response([
            'message' => __('success_action', ['model' => __('product_type'), 'action' => __('added')]),
            'product_type' => new ProductTypeResource($product_type)
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, ProductType $product_type)
    {
        $this->authorize('update', $product_type);

        $request->validate([
            'name' => 'sometimes|min:2|max:100',
            'description' => 'sometimes|min:2|max:1000',
            'position' => 'sometimes|numeric',
            'photos' => 'sometimes|array',
            'photos.*.image' => 'required_with:photos|image',
            'photos.*.title' => 'sometimes|min:2|max:100',
            'photos.*.description' => 'sometimes|min:2|max:199',
            'photos.*.is_main' => 'sometimes|in:0,1',
            'product_type_id' => 'sometimes|exists:product_types,id'
        ]);

        $product_type->update($request->only(['position', 'product_type_id']));

        if ($request->photos)
        {
            Helpers::addPhotosToModel($product_type, $request->photos);
        }

        return response([
            'message' => __('success_action', ['model' => __('product_type'), 'action' => __('updated')]),
            'product_type' => new ProductTypeResource($product_type->fresh())
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy(ProductType $product_type)
    {
        $this->authorize('delete', $product_type);

        $product_type->delete();

        return response([
            'message' => __('success_action', ['model' => __('product_type'), 'action' => __('deleted')])
        ]);
    }

    public function updateMedia(Request $request, ProductType $product_type, Media $media)
    {
        if ($media->mediable->id == $product_type->id || auth('api')->user()->can('update_product_type_media')) {
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
        abort(401, 'This media doesn\'t belong to that product type');
    }

    public function destoryMedia(ProductType $product_type, Media $media)
    {
        if ($media->mediable->id == $product_type->id || auth('api')->user()->can('delete_product_type_media')) {
            $product_type->deleteMedia($media);

            return response([
                'message' => __('success_action', ['model' => __('media'), 'action' => __('deleted')])
            ]);
        }
        abort(401, 'This media doesn\'t belong to that product type');
    }
}
