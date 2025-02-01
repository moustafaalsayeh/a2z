<?php

namespace Modules\Product\Http\Requests;

use Illuminate\Validation\Rule;
use Modules\Product\Entities\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Modules\Outlet\Entities\Outlet;
use Modules\ProductSpecification\Entities\ProductSpecification;

class CartItemStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'outlet_id' => 'required|exists:outlets,id',
            'product_id' => [
                'bail',
                'required',
                'exists:products,id',
                function ($attribute, $value, $fail) {
                    if (Product::find($value)->outlet->id != $this->outlet_id) {
                        $fail(__('product_doesnt_belong'));
                    }
                }
            ],
            'quantity' => 'required|numeric',
            'specifications_answers' =>[
                'sometimes',
                'array'
            ],
            'specifications_answers.*.specification_id' => [
                'bail',
                'required_with:specifications_answers',
                'exists:product_specifications,id'
            ],
            'specifications_answers.*.answer' => 'required_with:specifications_answers|max:1000'
        ];
    }

    protected function getValidatorInstance()
    {
        return parent::getValidatorInstance()->after(function ($validator) {
            // Call the after method of the FormRequest (see below)
            $this->after($validator);
        });
    }

    public function after($validator)
    {

        if(!$this->specifications_answers)
        {
            $this['specifications_answers'] = [];
        }

        foreach ($this->specifications_answers as $key => $spec) {
            $spec_model = ProductSpecification::find($spec['specification_id']);
            if(!$spec_model)
                return;

            if ($spec_model->is_required && !$spec['answer']) { //make sure the required provided specs have answer
                $validator->errors()->add('specifications_answers.' . $key . '.answer', __('required'));
            }

            //make sure the selected options for checkbox or radio spec
            //are included in this spec options
            if ($spec_model->type == 'checkbox' || $spec_model->type == 'radio') {
                $spec_answer_selected_ids = explode(',', $spec['answer']);

                if($spec_model->type == 'radio' && count($spec_answer_selected_ids) > 1)
                {
                    $validator->errors()->add('specifications_answers.' . $key . '.answer', __('select_one_option'));
                }

                $spec_allowed_ids = $spec_model->options()->pluck('id')->toArray();
                foreach ($spec_answer_selected_ids as $index => $id) {
                    if(! in_array((int) $id, $spec_allowed_ids))
                    {
                        $validator->errors()->add('specifications_answers.' . $key . '.answer', __('must_include_correct_optins_ids'));
                    }
                }
            }
        }

        //make sure all required specs are provided
        $selected_product = Product::find($this->product_id);
        $required_product_specs_ids = $selected_product
            ->productSpecifications()
            ->where('is_required', true)
            ->pluck('product_specifications.id');
        $required_outlet_product_specs_ids = Outlet::find($selected_product->outlet_id)
            ->productSpecifications()
            ->where('is_required', true)
            ->pluck('product_specifications.id');
        $all_required_product_specs = $required_product_specs_ids->merge($required_outlet_product_specs_ids);
        $provided_product_specs_ids = $this->specifications_answers ? array_column($this->specifications_answers, 'specification_id') : [];
        foreach ($all_required_product_specs as $key => $spec_id) {
            if(!in_array($spec_id, $provided_product_specs_ids))
            {
                $validator->errors()->add('specifications_answers' , __('product_specification') . ' ' . $spec_id . ' ' .  __('required'));
            }
        }
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
