<?php

namespace Modules\Address\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\APIAuth\Entities\User;
use Modules\Outlet\Entities\Outlet;

class AddressUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'country_id' => 'sometimes|exists:countries,id',
            'city_id' => 'sometimes|exists:cities,id',
            'title' => 'sometimes|min:3|max:255',
            'state' => 'sometimes|min:3|max:50',
            'postal_code' => 'sometimes|min:3|max:50',
            'street' => 'sometimes|min:1|max:100',
            'building' => 'sometimes|min:1|max:100',
            'apartment' => 'sometimes|min:1|max:100',
            'flat' => 'sometimes|min:1|max:50',
            'address_details' => 'sometimes|min:1|max:255',
            'landmark' => 'sometimes|min:1|max:100',
            'phone' => 'sometimes|min:1|max:50',
            'mobile' => 'sometimes|min:1|max:50',
            'lng' => 'sometimes|numeric',
            'lat' => 'sometimes|numeric',
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
        if($this->address->addressable == User::class && $this->address->addressable->id != auth('api')->user()->id)
        {
            return false;
        }
        if ($this->address->addressable == Outlet::class && $this->address->addressable->user->id != auth('api')->user()->id)
        {
            return false;
        }
        return true;
    }
}
