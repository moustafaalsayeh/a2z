<?php

namespace Modules\Product\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Media\Entities\Media;
use Illuminate\Routing\Controller;
use Modules\APIAuth\Helpers\Helpers;
use Modules\Product\Entities\Product;
use Illuminate\Database\Eloquent\Builder;
use Modules\Product\Filters\ProductFilter;
use Modules\Product\Transformers\ProductResource;
use Modules\Product\Http\Requests\ProductStoreRequest;
use Modules\Product\Http\Requests\ProductUpdateRequest;
use Modules\Product\Transformers\ProductSimpleResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProductController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(ProductFilter $request)
    {
        // dd(config('translatable.fallback_locale'));
        $products = Product::filter($request)->paginate(20);

        return ProductSimpleResource::collection($products);
    }

    public function indexUser(Request $request)
    {
        $outlet = $request->query('outlet');
        if ($outlet)
        {
            $products = Product::whereHas('outlet', function (Builder $query) use ($outlet) {
                return $query->where('outlets.id', $outlet);
            })
            ->whereHas('outlet.user', function (Builder $query) {
                return $query->where('id', auth('api')->user()->id);
            })
            ->get();
        }
        else {
            $products = Product::whereHas('outlet.user', function (Builder $query) {
                return $query->where('id', auth('api')->user()->id);
            })
            ->get();
        }

        return response([
            'message' => __('success_action', ['model' => __('product'), 'action' => __('retrieved')]),
            'products' => ProductSimpleResource::collection($products)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(ProductStoreRequest $request)
    {
        $this->authorize('create', Product::class);

        $product = Product::create($request->only('outlet_id', 'price', 'product_type_id'));

        if ($request->photos)
        {
            Helpers::addPhotosToModel($product, $request->photos);
        }

        return response([
            'message' => __('success_action', ['model' => __('product'), 'action' => __('added')]),
            'product' => new ProductResource($product->fresh())
        ]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show(Product $product)
    {
        return response([
            'message' => __('success_action', ['model' => __('product'), 'action' => __('retrieved')]),
            'products' => new ProductResource($product)
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(ProductUpdateRequest $request, Product $product)
    {
        $this->authorize('update', $product);

        $product->update($request->only('price', 'product_type_id'));

        if ($request->photos)
        {
            Helpers::addPhotosToModel($product, $request->photos);
        }

        return response([
            'message' => __('success_action', ['model' => __('product'), 'action' => __('updated')]),
            'products' => new ProductResource($product->fresh())
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);

        $product->delete();

        return response([
            'message' => __('success_action', ['model' => __('product'), 'action' => __('deleted')])
        ]);
    }

    public function updateMedia(Request $request, Product $product, Media $media)
    {
        if ($media->mediable->id == $product->id || auth('api')->user()->can('update_product_media')) {
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
        abort(401, 'This media doesn\'t belong to that product');
    }

    public function destoryMedia(Product $product, Media $media)
    {
        if ($media->mediable->id == $product->id || auth('api')->user()->can('delete_product_media')) {
            $product->deleteMedia($media);

            return response([
                'message' => __('success_action', ['model' => __('media'), 'action' => __('deleted')])
            ]);
        }
        abort(401, 'This media doesn\'t belong to that product');
    }
}
