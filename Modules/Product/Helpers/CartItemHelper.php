<?php

namespace Modules\Product\Helpers;

trait CartItemHelper
{
    public function createItem($outlet_id, $product_id, $quantity, $specifications_answers = [])
    {
        $user = auth('api')->user();

        if (!$user->carts()->where('outlet_id', $outlet_id)->first()) {
            $user->carts()->create(['outlet_id' => $outlet_id]);
        }

        $outlet_cart = $user->carts()->where('outlet_id', $outlet_id)->first();

        $already_added_item = $this->itemAlreadyAdded($outlet_cart, $product_id, $specifications_answers);

        if ($already_added_item) {
            $already_added_item->quantity += $quantity;
            $already_added_item->save();
        }
        else
        {
            $cart_item = $outlet_cart->items()->create(['product_id' => $product_id, 'quantity' => $quantity]);

            if ($specifications_answers)
            {
                foreach ($specifications_answers as $key => $spec_answer) {
                    $cart_item->specificationsAnswers()->create([
                        'prod_spec_id' => $spec_answer['specification_id'],
                        'answer' => $spec_answer['answer'],
                    ]);
                }
            }
        }
        return $outlet_cart->fresh();
    }

    private function itemAlreadyAdded($outlet_cart, $product_id, $specifications_answers)
    {
        if ($cart_items = $outlet_cart->items()->where('product_id', $product_id)->get()) {
            $matched_item = false;
            foreach ($cart_items as $key => $cart_item) {
                $num_of_matched_items = 0;
                if ($cart_item->specificationsAnswers->count() == count($specifications_answers))
                {
                    foreach ($specifications_answers as $key => $spec_answer) {
                        $cart_item_spec = $cart_item->specificationsAnswers()->where([
                            'prod_spec_id' => $spec_answer['specification_id'],
                            'answer' => $spec_answer['answer'],
                        ])->first();

                        if ($cart_item_spec != null) {
                            $num_of_matched_items += 1;
                        }
                    }
                    if ($num_of_matched_items == $cart_item->specificationsAnswers->count()) {
                        $matched_item = $cart_item;
                        break;
                    }
                }
            }
            return $matched_item;
        }
    }
}
