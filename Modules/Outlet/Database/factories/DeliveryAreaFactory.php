<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Modules\Outlet\Entities\DeliveryArea;

$factory->define(DeliveryArea::class, function (Faker $faker) {
    return [
        'title' => 'Zamalek, Egypt',
        'points' => [
            ["30.073203","31.222400"],
            ["30.066322","31.215238"],
            ["30.039246","31.221646"],
            ["30.038628","31.224861"],
            ["30.046492","31.228485"],
            ["30.073203","31.222400"]
        ],
        'delivery_time' => 45,
        'delivery_fees' => 10,
        'min_order' => 0
    ];
});
