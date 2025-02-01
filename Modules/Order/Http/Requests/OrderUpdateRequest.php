<?php

namespace Modules\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Outlet\Entities\Outlet;
use Modules\Product\Entities\Cart;

class OrderUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'status' => [
                'sometimes',
                'in:accepted,refused,prepared,delivering,cancelled,completed',
                function ($attribute, $value, $fail)
                {
                    if(auth('api')->user()->type == 'buyer' && $value != 'cancelled')
                    {
                        $fail(__('not_allowed_status'));
                    }
                    if (auth('api')->user()->type == 'seller' && !in_array($value, ['accepted', 'refused', 'prepared']))
                    {
                        $fail(__('not_allowed_status'));
                    }
                    if (auth('api')->user()->type == 'delivery' && !in_array($value, ['delivering', 'completed']))
                    {
                        $fail(__('not_allowed_status'));
                    }
                    if($value == 'accepted' && $this->order->status != 'refused' && $this->order->status != 'waiting')
                    {
                        $fail(__('not_allowed_status'));
                    }
                    if($value == 'refused' && $this->order->status != 'waiting')
                    {
                        $fail(__('not_allowed_status'));
                    }
                    if($value == 'prepared' && $this->order->status != 'accepted')
                    {
                        $fail(__('not_allowed_status'));
                    }
                    if($value == 'delivering' && $this->order->status != 'prepared')
                    {
                        $fail(__('not_allowed_status'));
                    }
                    if($value == 'completed' && $this->order->status != 'delivering')
                    {
                        $fail(__('not_allowed_status'));
                    }
                    if($value == 'cancelled' && (($this->order->seconds_left_for_cancel <= 0  || !in_array($this->order->status, ['waiting', 'refused'])) && auth('api')->user()->type == 'buyer'))
                    {
                        $fail(__('not_allowed_status'));
                    }
                }
            ],
            'prepration_time_minutes' => 'required_if:status,accepted|integer',
            'prepration_time_days' => 'required_if:status,accepted|integer',
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

    public function getValidatorInstance()
    {
        if($this->status == 'delivering')
            $this['delivery_man_id'] = auth('api')->user()->id;

        return parent::getValidatorInstance();
    }
}
