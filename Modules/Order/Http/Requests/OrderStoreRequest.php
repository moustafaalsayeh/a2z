<?php

namespace Modules\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Address\Entities\Address;
use Modules\APIAuth\Entities\User;
use Modules\Outlet\Entities\Outlet;
use Modules\Product\Entities\Cart;

class OrderStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'cart_id' => [
                'bail',
                'required',
                'exists:carts,id',
                function($attribute, $value, $fail)
                {
                    if(Cart::find($value)->user->id != auth('api')->user()->id)
                    {
                        $fail(__('cart_doesnt_belongs'));
                    }
                    if(!Cart::find($value)->items->count())
                    {
                        $fail(__('cart_empty'));
                    }
                }
            ],
            'address_id' => [
                'bail',
                'required',
                'exists:addresses,id',
                function($attribute, $value, $fail)
                {
                    $address = Address::findOrFail($value);
                    if($address->addressable_type != User::class || $address->addressable_id != auth('api')->user()->id)
                    {
                        $fail(__('address_doesnt_belong'));
                    }
                }
            ],
            'payment_method' => 'required|in:cash',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
