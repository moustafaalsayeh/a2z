<?php

namespace Modules\Outlet\Http\Requests;

use Illuminate\Support\Str;
use App\Rules\URLWithoutProtocol;
use Modules\APIAuth\Entities\User;
use Illuminate\Foundation\Http\FormRequest;

class OutletStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => [
                'bail',
                'required',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    if (User::find($value)->type != 'seller') {
                        $fail('provided user_id must be for a user of type Seller');
                    }
                }
            ],
            'name' => 'required|min:2|max:100',
            'phone' => 'sometimes|min:2|max:100',
            'email' => 'required|email',
            'info' => 'sometimes|min:3|max:1000',
            'website' => ['sometimes', new URLWithoutProtocol],
            'rank' => 'sometimes|numeric|min:0|max:5',
            'photos' => 'sometimes|array',
            'photos.*.image' => 'required_with:photos|image',
            'photos.*.title' => 'sometimes|min:2|max:100',
            'photos.*.description' => 'sometimes|min:2|max:199',
            'photos.*.is_main' => 'sometimes|in:0,1',
            'working_hours.*.day' => 'required_with:working_hours|in:saturday,sunday,monday,tuesday,wednesday,thursday,friday',
            'working_hours.*.time_from'  => 'required_with:working_hours|date_format:H:i',
            'working_hours.*.time_to'  => 'required_with:working_hours|date_format:H:i',
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
            if($this['website'])
            {
                $this['website'] = !Str::contains($this['website'], 'http') ? 'http://' . $this['website'] : $this['website'];
            }
        });
    }
}
