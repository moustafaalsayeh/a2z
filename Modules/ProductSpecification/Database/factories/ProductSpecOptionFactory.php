<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Modules\ProductSpecification\Entities\ProductSpecificationOption;

$factory->define(ProductSpecificationOption::class, function (Faker $faker) {
    return [
        'price' => $faker->numberBetween(5, 150),
    ];
});
