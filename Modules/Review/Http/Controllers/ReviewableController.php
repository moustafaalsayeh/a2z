<?php

namespace Modules\Review\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductType;
use Modules\Review\Entities\Reviewable;
use Modules\Review\Filters\ReviewableFilter;
use Modules\Review\Transformers\ReviewableResource;

class ReviewableController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(ReviewableFilter $request)
    {
        return response([
            'message' => __('success_action', ['model' => __('review_items'), 'action' => __('retreived')]),
            'review_item' => ReviewableResource::collection(Reviewable::filter($request)->get())
        ]);
    }


    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Reviewable::class);

        $reviewable_type = $request->reviewable_type;
        $request->validate([
            'reviewable_type' => 'bail|required|in:products,product_types',
            'reviewable_id' => 'required|exists:'.$reviewable_type.',id',
            'title' => 'required|min:3|max:100'
        ]);

        $request['reviewable_type'] = $reviewable_type == 'products' ? Product::class : ProductType::class;

        $review_item = Reviewable::create($request->only(['reviewable_type', 'reviewable_id']));

        return response([
            'message' => __('success_action', ['model' => __('review_items'), 'action' => __('retreived')]),
            'review_item' => new ReviewableResource($review_item)
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, Reviewable $review_item)
    {
        $this->authorize('update', $review_item);

        $request->validate([
            'title' => 'required|min:3|max:100'
        ]);

        $review_item->update($request->only([]));

        return response([
            'message' => __('success_action', ['model' => __('review_items'), 'action' => __('updated')]),
            'review_item' => new ReviewableResource($review_item->fresh())
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy(Reviewable $review_item)
    {
        $this->authorize('delete', $review_item);

        $review_item->delete();

        return response([
            'message' => __('success_action', ['model' => __('review_items'), 'action' => __('deleted')])
        ]);
    }
}
