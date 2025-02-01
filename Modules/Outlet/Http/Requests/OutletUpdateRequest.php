<?php

namespace Modules\Outlet\Http\Requests;

use Illuminate\Support\Str;
use App\Rules\URLWithoutProtocol;
use Illuminate\Foundation\Http\FormRequest;

class OutletUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'sometimes|min:2|max:100',
            'phone' => 'nullable|min:2|max:100',
            'email' => 'sometimes|email',
            'info' => 'sometimes|min:3|max:1000',
            'website' => ['sometimes' , new URLWithoutProtocol],
            'rank' => 'sometimes|numeric|min:0|max:5',
            'photos' => 'sometimes|array',
            'photos.*.image' => 'required_with:photos|image',
            'photos.*.title' => 'sometimes|min:2|max:100',
            'photos.*.description' => 'sometimes|min:2|max:199',
            'photos.*.is_main' => 'sometimes|in:0,1',
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
