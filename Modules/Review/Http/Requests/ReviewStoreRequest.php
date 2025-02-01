<?php

namespace Modules\Review\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_id' => 'required|exists:products,id',
            'commnet' => 'sometimes|min:2|max:1000',
            'rates' => 'required|array',
            'rates.*.reviewable_id' => 'required|exists:reviewables,id',
            'rates.*.rate' => 'required|in:1,2,3,4,5'
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth('api')->user()->type == 'buyer' && auth('api')->user()->reviews()->where('product_id', $this->product_id)->first() == null;
    }

    public function withValidator($validator)
    {
        $this['user_id'] = auth('api')->user()->id;
    }
}
