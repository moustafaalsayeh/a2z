<?php

namespace Modules\Outlet\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Outlet\Entities\Outlet;

class DeliveryAreaUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'sometimes|min:3|max:100',
            'points' => 'sometimes|array',
            'points.*' => 'required_with:points|string',
            'delivery_time' => 'sometimes|numeric',
            'delivery_fees' => 'sometimes|numeric',
            'min_order' => 'sometimes|numeric',
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
        return parent::getValidatorInstance(function ($validator){
            if($this->has('points'))
            {
                $new_points = [];
                foreach ($this->points as $key => $point) {
                    $new_points[] = array_map('trim', explode(',', $this['points'][$key]));
                }
                $this['points'] = $new_points;
            }
            
            if($this->has('points') && array_diff($this->points[0], $this->points[sizeof($this->points) - 1]))
            {
                $validator->errors()->add('points', __('first_last_match'));
            }
        });
    }
}
