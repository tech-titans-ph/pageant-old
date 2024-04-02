<?php

// @var $factory \Illuminate\Database\Eloquent\Factory

use App\{Contest, Judge, User};
use Faker\Generator as Faker;

$factory->define(Judge::class, function (Faker $faker) {
    return [
        'contest_id' => factory(Contest::class),
        'name' => $faker->name,
    ];
});
