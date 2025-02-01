<?php

namespace Modules\ProductSpecification\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Modules\ProductSpecification\Entities\ProductSpecification;
use Modules\ProductSpecification\Entities\ProductSpecificationOption;
use Modules\ProductSpecification\Http\Requests\ProdcutSpecificationOptionStoreRequest;
use Modules\ProductSpecification\Http\Requests\ProdcutSpecificationOptionUpdateRequest;
use Modules\ProductSpecification\Transformers\ProductSpecificationResource;
use Modules\ProductSpecification\Http\Requests\ProdcutSpecificationUpdateRequest;
use Modules\ProductSpecification\Transformers\ProductSpecificationOptionResource;

class ProductSpecificationOptionController extends Controller
{
    use AuthorizesRequests;

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(ProdcutSpecificationOptionStoreRequest $request)
    {
        $this->authorize('create', ProductSpecificationOption::class);

        $product_spec_option = ProductSpecificationOption::create($request->only(['prod_spec_id', 'price']));

        return response([
            'message' => __('success_action', ['model' => __('product_specification_option'), 'action' => __('added')]),
            'product_specification_option' => new ProductSpecificationOptionResource($product_spec_option)
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(ProdcutSpecificationOptionUpdateRequest $request, ProductSpecificationOption $product_specification_option)
    {
        $this->authorize('update', $product_specification_option);

        $product_specification_option->update($request->only(['price']));

        return response([
            'message' => __('success_action', ['model' => __('product_specification_option'), 'action' => __('updated')]),
            'product_specification_option' => new ProductSpecificationOptionResource($product_specification_option)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy(ProductSpecificationOption $product_specification_option)
    {
        $this->authorize('delete', $product_specification_option);

        $product_specification_option->delete();

        return response([
            'message' => __('success_action', ['model' => __('product_specification_option'), 'action' => __('deleted')])
        ]);
    }
}
