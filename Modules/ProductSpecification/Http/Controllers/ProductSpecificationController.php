<?php

namespace Modules\ProductSpecification\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Modules\Outlet\Entities\Outlet;
use Modules\Product\Entities\Product;
use Modules\ProductSpecification\Entities\ProductSpecification;
use Modules\ProductSpecification\Filters\ProductSpecificationFilter;
use Modules\ProductSpecification\Transformers\ProductSpecificationResource;
use Modules\ProductSpecification\Http\Requests\ProdcutSpecificationStoreRequest;
use Modules\ProductSpecification\Http\Requests\ProdcutSpecificationUpdateRequest;

class ProductSpecificationController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(ProductSpecificationFilter $request)
    {
        return response([
            'message' => __('success_action', ['model' => __('product_specification'), 'action' => __('retrieved')]),
            'product_specification' => ProductSpecificationResource::collection(ProductSpecification::filter($request)->get())
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(ProdcutSpecificationStoreRequest $request)
    {
        $this->authorize('create', ProductSpecification::class);

        $product_spec = ProductSpecification::create($request->only(['type', 'is_required']));

        if($request->has('options'))
        {
            foreach ($request->options as $key => $option) {
                $spec_option = $product_spec->options()->create([
                    'price' => array_key_exists('price', $option) ? $option['price'] : 0
                ]);
                $spec_option->translate(['value' => $option['value']]);
            }
        }

        if($request->specificable_type == Product::class)
        {
            $product_spec->products()->sync($request->specificable_ids);
        }
        else if($request->specificable_type == Outlet::class)
        {
            $product_spec->outlets()->sync($request->specificable_ids);
        }
        // foreach ($request->specificable_ids as $key => $specificable_id) {
        // }

        return response([
            'message' => __('success_action', ['model' => __('product_specification'), 'action' => __('added')]),
            'product_specification' => new ProductSpecificationResource($product_spec)
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(ProdcutSpecificationUpdateRequest $request, ProductSpecification $product_specification)
    {
        $this->authorize('update', $product_specification);

        $product_specification->update($request->only(['is_required']));

        return response([
            'message' => __('success_action', ['model' => __('product_specification'), 'action' => __('updated')]),
            'product_specification' => new ProductSpecificationResource($product_specification)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy(ProductSpecification $product_specification)
    {
        $this->authorize('delete', $product_specification);

        $product_specification->delete();

        return response([
            'message' => __('success_action', ['model' => __('product_specification'), 'action' => __('deleted')])
        ]);
    }
}
