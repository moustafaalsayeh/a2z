<?php

namespace Modules\Product\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Product\Entities\Cart;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\CartItem;
use Modules\Product\Filters\CartFilter;
use Modules\Product\Transformers\CartResource;
use Modules\Product\Transformers\CartAdminResource;
use Modules\Product\Http\Requests\CartItemStoreRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Modules\Product\Helpers\CartItemHelper;
use Modules\Product\Transformers\CartAdminSimpleResource;

class CartController extends Controller
{
    use AuthorizesRequests, CartItemHelper;

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function indexAdmin(CartFilter $query)
    {
        $this->authorize('viewAny', Cart::class);

        return CartAdminSimpleResource::collection(Cart::whereHas('items')->filter($query)->paginate(20));
    }

    public function show(Cart $cart)
    {
        $this->authorize('viewAny', Cart::class);

        return response([
            'message' => __('success_action', ['model' => __('cart'), 'action' => __('retrieved')]),
            'cart' => new CartAdminResource($cart)
        ]);
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(CartFilter $query)
    {
        $user = auth('api')->user();

        return response([
            'message' => __('success_action', ['model' => __('cart'), 'action' => __('retrieved')]),
            'carts' => CartResource::collection(Cart::where('user_id', $user->id)->whereHas('items')->filter($query)->get())
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(CartItemStoreRequest $request)
    {

        $this->authorize('create', Cart::class);

        $outlet_cart = $this->createItem(
            $request->outlet_id,
            $request->product_id,
            $request->quantity,
            $request->specifications_answers
        );

        return response([
            'message' => __('success_action', ['model' => __('cart_item'), 'action' => __('added')]),
            'cart' => new CartResource($outlet_cart)
        ]);
    }

    public function destroy(Cart $cart)
    {
        $this->authorize('delete', $cart);

        $cart->delete();

        return response([
            'message' => __('success_action', ['model' => __('cart'), 'action' => __('deleted')])
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function updateItem(Request $request, CartItem $cart_item)
    {
        $this->authorize('updateItem', $cart_item->cart);

        $request->validate([
            'quantity' => 'required|numeric'
        ]);

        $cart_item->update($request->only(['quantity']));

        return response([
            'message' => __('success_action', ['model' => __('cart_item'), 'action' => __('updated')]),
            'cart' => new CartResource($cart_item->cart)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function removeItem(CartItem $cart_item)
    {
        $this->authorize('deleteItem', $cart_item->cart);

        $cart_item->delete();

        return response([
            'message' => __('success_action', ['model' => __('cart_item'), 'action' => __('deleted')])
        ]);
    }
}
