<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Modules\Address\Entities\Address;

$factory->define(Address::class, function (Faker $faker) {
    return [
        'country_id' => 68,
        'title' => $faker->title,
        'address_details' => $faker->sentence(10),
        'state' => $faker->firstName(),
        'postal_code' => $faker->numberBetween(10000, 99999),
        'street' => $faker->firstName(),
        'building' => $faker->numberBetween(1, 500),
        'apartment' => $faker->numberBetween(1, 250),
        'flat' => $faker->numberBetween(1, 50),
        'mobile' => $faker->randomElement(['2010', '2012', '2011']) . $faker->numberBetween(10000000, 99999999),
        'lat' => 30.063719,
        'lng' => 31.219503,
        'is_primary' => 1
    ];
});
