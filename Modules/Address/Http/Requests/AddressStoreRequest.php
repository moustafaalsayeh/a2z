<?php

namespace Modules\Address\Http\Requests;

use Modules\APIAuth\Entities\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Outlet\Entities\Outlet;

class AddressStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'addressable_type' => 'required|in:user,outlet',
            'addressable_id' => [
                'bail',
                'required_if:addressable_type,outlet',
                'exists:outlets,id',
                function($attribute, $value, $fail)
                {
                    if($this->addressable_type == 'outlet' && outlet::findOrFail($value)->user->id != auth('api')->user()->id)
                    {
                        $fail(__('outlet_doesnt_belong'));
                    }
                }
            ],
            'country_id' => 'sometimes|exists:countries,id',
            'city_id' => 'sometimes|exists:cities,id',
            'title' => 'sometimes|min:3|max:255',
            'state' => 'sometimes|min:3|max:50',
            'postal_code' => 'sometimes|min:3|max:50',
            'street' => 'required|min:1|max:100',
            'building' => 'required|min:1|max:100',
            'apartment' => 'sometimes|min:1|max:50',
            'floor' => 'sometimes|min:1|max:50',
            'address_details' => 'required|min:1|max:255',
            'landmark' => 'sometimes|min:1|max:100',
            'phone' => 'sometimes|min:1|max:50',
            'mobile' => 'sometimes|min:1|max:50',
            'lng' => 'required|numeric',
            'lat' => 'required|numeric',
            'is_primary' => 'sometimes|boolean',
            'is_shipping' => 'sometimes|boolean',
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
        return parent::getValidatorInstance()->after(function ($validator) {
            // Call the after method of the FormRequest (see below)
            $this->after($validator);
        });
    }

    public function after($validator){
        if ($this->addressable_type == 'user') {
            $this['addressable_type'] = User::class;
            $this['addressable_id'] = auth('api')->user()->id;
        }
        else{
            $this['addressable_type'] = Outlet::class;
        }
    }
}
