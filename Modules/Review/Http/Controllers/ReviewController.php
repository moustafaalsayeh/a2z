<?php

namespace Modules\Review\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Review\Entities\Review;
use Modules\Review\Filters\ReviewFilter;
use Modules\Review\Http\Requests\ReviewStoreRequest;
use Modules\Review\Http\Requests\ReviewUpdateRequest;
use Modules\Review\Transformers\ReviewResource;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(ReviewFilter $request)
    {
        return ReviewResource::collection(Review::filter($request)->paginate(20));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(ReviewStoreRequest $request)
    {
        $review = Review::create($request->only(['product_id', 'user_id', 'comment']));

        $review->rates()->createMany($request->rates);

        return response([
            'message' => __('success_action', ['model' => __('review'), 'action' => __('stored')]),
            'review_item' => new ReviewResource($review->fresh())
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(ReviewUpdateRequest $request, Review $review)
    {
        $review->update([
            'comment' => $request->comment
        ]);

        return response([
            'message' => __('success_action', ['model' => __('review'), 'action' => __('updated')]),
            'review_item' => new ReviewResource($review->fresh())
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy(Review $review)
    {
        if(auth('api')->user()->type == 'buyer' && $review->user->id == auth('api')->user()->id)
        {
            $review->delete();

            return response([
                'message' => __('success_action', ['model' => __('review'), 'action' => __('deleted')])
            ]);
        }
        abort(401);
    }
}
