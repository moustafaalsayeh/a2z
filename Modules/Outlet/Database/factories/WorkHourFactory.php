<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Modules\Outlet\Entities\WorkHour;

$factory->define(WorkHour::class, function (Faker $faker) {
    return [
        'day' => $faker->dayOfWeek(),
        'time_from' => $faker->randomElement(['07:00:00', '08:00:00', '09:00:00', '10:00:00', '11:00:00']),
        'time_to' => $faker->randomElement(['17:00:00', '18:00:00', '19:00:00', '20:00:00', '21:00:00']),
    ];
});
