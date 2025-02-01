<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Modules\ProductSpecification\Entities\ProductSpecification;

$factory->define(ProductSpecification::class, function (Faker $faker) {
    return [
        'is_required' => $faker->randomElement([0, 1]),
        'type' => $faker->randomElement(['text','checkbox','radio'])
    ];
});
