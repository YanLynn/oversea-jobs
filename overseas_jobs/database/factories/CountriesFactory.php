<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Country;
use Faker\Generator as Faker;

$factory->define(Country::class, function (Faker $faker) {
    return [
        'continent_name' => $faker->realText(10),
        'country_name' => $faker->realText(10)
    ];
});
