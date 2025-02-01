<?php

namespace Modules\Payment\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Payment\Transformers\PaymentMethodResource;

class PaymentMethodController extends Controller
{
    /**
     * Returns the payment methods the user has saved
     *
     * @param Request $request The request data from the user.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        return response([
            'message' => __('success_action', ['model' => __('payment_method'), 'action' => __('retrieved')]),
            'payment_method' => $user->hasPaymentMethod() ? PaymentMethodResource::collection($user->paymentMethods()) : []
        ]);
    }

    /**
     * Adds a payment method to the current user.
     *
     * @param Request $request The request data from the user.
     */
    public function store(Request $request)
    {
        $request->validate([
            'payment_method_id' => 'required'
        ]);

        $user = $request->user();
        $paymentMethodID = $request->get('payment_method_id');

        if ($user->stripe_id == null) {
            $user->createAsStripeCustomer();
        }

        $user->addPaymentMethod($paymentMethodID);

        return response([
            'message' => __('success_action', ['model' => __('payment_method'), 'action' => __('added')]),
        ]);
    }

    /**
     * Removes a payment method for the current user.
     *
     * @param Request $request The request data from the user.
     */
    public function destory(Request $request)
    {
        $request->validate([
            'payment_method_id' => 'required'
        ]);

        $user = $request->user();
        $paymentMethodID = $request->get('payment_method_id');

        $paymentMethod = $user->findPaymentMethod($paymentMethodID);

        if($paymentMethod)
        {
            $paymentMethod->delete();

            return response([
                'message' => __('success_action', ['model' => __('payment_method'), 'action' => __('deleted')]),
                'payment_method' => new PaymentMethodResource($paymentMethod)
            ]);
        }

        return response([
            'message' => __('not_found_message', ['model' => __('payment_method')])
        ], 404);
    }

    /**
     * Charge the current user with one time amout
     * @param Request $request
     * @return Response
     */
    public function charge(Request $request)
    {
        $request->validate([
            'payment_method_id' => 'required',
            'amount' => 'required|numeric'
        ]);

        $user = $request->user();
        $paymentMethodID = $request->get('payment_method_id');

        $paymentMethod = $user->findPaymentMethod($paymentMethodID);

        if ($paymentMethod) {
            try {
                $payment = $user->charge(100, $paymentMethod);
            } catch (Exception $e) {
                return response([
                    'message' => __('fail_action', ['model' => __('payment'), 'action' => __('proccessed')])
                ], 404);
            }

            return response([
                'message' => __('success_action', ['model' => __('payment_method'), 'action' => __('deleted')]),
                'payemtn' => $payment
            ]);
        }

        return response([
            'message' => __('not_found_message', ['model' => __('payment_method')])
        ], 404);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('payment::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('payment::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
