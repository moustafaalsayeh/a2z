<?php

namespace Modules\Outlet\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Outlet\Entities\Outlet;

class DeliveryAreaStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'outlet_id' => [
                'bail',
                'required',
                'exists:outlets,id',
                function ($attribute, $value, $fail)
                {
                    if (auth('api')->user()->type != 'admin' && Outlet::find($value)->user->id != auth('api')->user()->id) {
                        $fail(__('outlet_doesnt_belong'));
                    }
                }
            ],
            'title' => 'sometimes|min:3|max:100',
            'points' => 'required|array',
            'points.*' => 'required|string',
            'delivery_time' => 'required|numeric',
            'delivery_fees' => 'required|numeric',
            'min_order' => 'required|numeric',
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

    protected function getValidatorInstance()
    {
        return parent::getValidatorInstance()->after(function ($validator){
            $new_points = [];
            foreach ($this->points as $key => $point) {
                $new_points[] = array_map('trim', explode(',', $this['points'][$key]));
            }
            $this['points'] = $new_points;

            if(array_diff($this->points[0], $this->points[sizeof($this->points) - 1]))
            {
                $validator->errors()->add('points', __('first_last_match'));
            }
        });
    }
}
