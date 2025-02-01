<?php

namespace Modules\Product\Helpers;

class ProductItemHelpers
{
    public static function ItemableQuantity($itemable)
    {
        $itemable_quantity = 0;
        foreach ($itemable->items as $key => $item) {
            $itemable_quantity += $item->quantity;
        }
        return $itemable_quantity;
    }

    public static function ItemableTotalPrice($itemable)
    {
        $itemable_total_price = 0;
        foreach ($itemable->items as $key => $item) {
            $itemable_total_price += $item->item_total_price;
        }
        return $itemable_total_price;
    }
}


