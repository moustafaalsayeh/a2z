<?php

namespace Modules\Order\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Order\Entities\Order;
use Illuminate\Routing\Controller;
use Modules\Product\Entities\Cart;
use Modules\Outlet\Entities\Outlet;
use Illuminate\Support\Facades\Mail;
use Modules\Address\Entities\Address;
use Modules\Order\Filters\OrderFilter;
use Modules\Product\Helpers\CartItemHelper;
use Modules\Order\Emails\OrderAcceptedEmail;
use Modules\Order\Events\OrderStatusChanged;
use Modules\Order\Jobs\OrderPlacedOutletJob;
use Modules\Order\Transformers\OrderResource;
use Illuminate\Validation\ValidationException;
use Modules\Product\Transformers\CartResource;
use Modules\Order\Emails\OrderPlacedBuyerEmail;
use Modules\Order\Emails\OrderPlacedOutletEmail;
use Modules\Order\Http\Requests\OrderStoreRequest;
use Modules\Order\Http\Requests\OrderUpdateRequest;
use Modules\Order\Transformers\OrderOutletResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Modules\Order\Events\OrderPrepared;
use Modules\Product\Entities\Product;
use Modules\ProductSpecification\Entities\ProductSpecification;
use Modules\ProductSpecification\Entities\ProductSpecificationOption;

class OrderController extends Controller
{
    use AuthorizesRequests, CartItemHelper;
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(OrderFilter $query)
    {
        $this->authorize('viewAny', Order::class);

        return OrderResource::collection(Order::filter($query)->paginate(20));
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function indexBuyer(OrderFilter $query)
    {
        $this->authorize('viewBuyer', Order::class);

        $user = auth('api')->user();

        return OrderResource::collection($user->orders()->filter($query)->paginate(20));
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function indexSeller(OrderFilter $query)
    {
        $this->authorize('viewSeller', Order::class);

        $user = auth('api')->user();

        $orders = Order::whereHas('outlet.user', function($query) use($user){
            $query->where('id', $user->id);
        })->filter($query);

        return OrderOutletResource::collection($orders->paginate(20));
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function indexDelivery(OrderFilter $query)
    {
        $this->authorize('viewDelivery', Order::class);

        $orders = Order::where('delivery_man_id', auth('api')->user()->id)->filter($query);

        return OrderResource::collection($orders->paginate(20));
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function indexPreparedOrders()
    {
        $this->authorize('viewDelivery', Order::class);

        $orders = Order::where('status', 'prepared');

        return OrderResource::collection($orders->paginate(20));
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);

        return response([
            'message' => __('success_action', ['model' => __('order'), 'action' => __('retrieved')]),
            'order' => new OrderResource($order)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(OrderStoreRequest $request)
    {
        $this->authorize('create', Order::class);

        $user = auth('api')->user();

        $cart = Cart::find($request->cart_id);

        $order = $user->orders()->create([
            'outlet_id' => $cart->outlet->id,
            'payment_method' => $request->payment_method,
            'status' => 'waiting'
        ]);
        $selected_address = Address::findOrFail($request->address_id);
        $request->user()->editAddress($selected_address, ['is_primary' => true]);
        $order->addAddress($selected_address->toArray());

        $delivery_area_info = $order->outlet->delivery_area_info;
        if(!$delivery_area_info)
        {
            $order->delete();
            throw ValidationException::withMessages(['address_id' => __('outlet_doesnt_cover')]);
        }
        $order->update([
            'delivery_fees' => $delivery_area_info->delivery_fees,
            'delivery_time' => $delivery_area_info->delivery_time,
        ]);


        $this->moveItemsFromCartToOrder($cart, $order);
        $order->refresh();

        try {
            Mail::to($user)->send(new OrderPlacedBuyerEmail($user->username, $user->email, $order->created_at));
            Mail::to($order->outlet->email)->send(new OrderPlacedOutletEmail($order));
        } catch (\Throwable $th) {
            //throw $th;
        }
        // OrderPlacedBuyerJob::dispatch($order->user->email, $order->user->username, $order->created_at);
        // OrderPlacedOutletJob::dispatch($order->outlet->email, $order);

        return response([
            'message' => __('success_action', ['model' => __('order'), 'action' => __('added')]),
            'order' => new OrderResource($order)
        ]);
    }

    private function moveItemsFromCartToOrder($cart, &$order)
    {
        foreach ($cart->items as $key => $item) {
            $order_item = $order->items()->create([
                'product_id' => $item->product->id,
                'product_name' => $item->product->name,
                'product_price' => $item->product->price,
                'product_quantity' => $item->quantity,
            ]);

            $product_main_media = $item->product->main_media;
            $order_item->addMedia(
                $product_main_media->type,
                $product_main_media->path,
                $product_main_media->thumb,
                $product_main_media->meduim,
                $product_main_media->large,
                $product_main_media->title,
                $product_main_media->description,
                true
            );

            foreach ($item->specificationsAnswers as $key2 => $spec_answer) {
                $order_item->orderItemSpecs()->create([
                    'prod_spec_id' => $spec_answer->prod_spec_id,
                    'spec_title' => $spec_answer->specification->title,
                    'answer_string' => $spec_answer->answer_string,
                    'answer_price' => $spec_answer->answer_price,
                    'answer_ids' => $spec_answer->getOriginal('answer'),
                ]);
            }

            $item->delete();
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(OrderUpdateRequest $request, Order $order)
    {
        $this->authorize('update', $order);

        $order->update($request->only(['status', 'prepration_time_minutes', 'prepration_time_days', 'delivery_man_id']));

        if($request->status == 'accepted')
        {
            // OrderAcceptedJob::dispatch($order->user->email, $order->user->username, $order->outlet->name, $order);
            try {
                //code...
                Mail::to($order->user)->send(new OrderAcceptedEmail($order->user->username, $order->outlet->name, $order));
            } catch (\Throwable $th) {
                //throw $th;
            }
        }

        if($request->has('status'))
        {
            event(new OrderStatusChanged($order->status, $order->id));
            if($request->status == 'prepared')
            {
                event(new OrderPrepared($order, $order->outlet->address, $order->address));
            }
        }

        return response([
            'message' => __('success_action', ['model' => __('order'), 'action' => __('updated')]),
            'order' => new OrderResource($order)
        ]);
    }

    public function reorderToCart(Order $order)
    {
        $cart = (object) [];

        if(!$this->isValidOrder($order))
        {
            return response([
                'message' => __('fail_action', ['model' => __('cart_items'), 'action' => __('added')])
            ]);
        }

        foreach ($order->items as $key => $item) {
            $specs = $item->orderItemSpecs;
            $spec_answers = $specs->map(function($spec){
                return ['specification_id' => $spec->prod_spec_id, 'answer' => $spec->answer_ids];
            });

            $cart = $this->createItem(
                $order->outlet_id,
                $item->product_id,
                $item->product_quantity,
                $spec_answers->toArray()
            );
        }

        return response([
            'message' => __('success_action', ['model' => __('cart_items'), 'action' => __('added')]),
            'cart' => new CartResource($cart)
        ]);
    }

    private function isValidOrder($order)
    {
        if(!$order->outlet || !$order->user)
        {
            return false;
        }
        foreach ($order->items as $key => $item) {
            $item_product = Product::where('id', $item->product_id)->first();
            if(!$item_product || $item_product->name != $item->product_name)
            {
                return false;
            }
            if($item->orderItemSpecs->count() == 0)
            {
                return true;
            }
            foreach ($item->orderItemSpecs as $key2 => $item_spec) {
                $product_spec = ProductSpecification::where('id', $item_spec->prod_spec_id)->first();
                if(!$product_spec || $product_spec->title != $item_spec->spec_title)
                {
                    return false;
                }
                if($product_spec->type == 'radio')
                {
                    $spec_answer_option = ProductSpecificationOption::where('id', $item_spec->answer_ids)->first();
                    if(!$spec_answer_option || $spec_answer_option->value != $item_spec->answer_string)
                    {
                        return false;
                    }
                }
                else if ($product_spec->type == 'checkbox')
                {
                    $items_answers_string = array_map('trim', explode(',', $item_spec->answer_string));
                    $items_answers_ids = array_map('trim', explode(',', $item_spec->answer_ids));
                    foreach ($items_answers_ids as $key3 => $spec_answer_id) {
                        $spec_answer_option = ProductSpecificationOption::where('id', $spec_answer_id)->first();
                        if (!$spec_answer_option || $spec_answer_option->value != $items_answers_string[$key3]) {
                            return false;
                        }
                    }
                }
            }
        }
        return true;
    }

    public function reorder(Order $order)
    {
        $this->authorize('reorder', $order);

        $new_order = $order->replicate();

        $new_order->push();
        $new_order->status = 'waiting';
        $new_order->save();

        foreach ($order->items as $key => $item) {
            $new_item = $new_order->items()->create([
                'itemable_type' => Order::class,
                'itemable_id' => $new_order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
            ]);

            foreach ($item->specificationsAnswers as $key2 => $spec_answer) {
                $new_item->specificationsAnswers()->create([
                    'prod_spec_id' => $spec_answer->prod_spec_id,
                    'answer' => $spec_answer->getOriginal('answer'),
                ]);
            }
        }

        try {
            Mail::to($new_order->user)->send(new OrderPlacedBuyerEmail($new_order->user->username, $new_order->user->email, $new_order->created_at));
            Mail::to($new_order->outlet->email)->send(new OrderPlacedOutletEmail($new_order));
        } catch (\Throwable $th) {
            //throw $th;
        }
        // OrderPlacedBuyerJob::dispatch($new_order->user->email, $new_order->user->username, $new_order->created_at);

        // OrderPlacedOutletJob::dispatch($new_order->outlet->email, $new_order);

        return response([
            'message' => __('success_action', ['model' => __('order'), 'action' => __('reordered')]),
            'order' => new OrderResource($new_order)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy(Order $order)
    {
        $this->authorize('update', $order);

        $order->delete();

        return response([
            'message' => __('success_action', ['model' => __('order'), 'action' => __('delete')])
        ]);
    }
}
