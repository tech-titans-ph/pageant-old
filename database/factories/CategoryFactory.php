<?php

// @var $factory \Illuminate\Database\Eloquent\Factory

use App\Category;
use App\Contest;
use Faker\Generator as Faker;

$factory->define(Category::class, function (Faker $faker) {
    return [
        'contest_id' => factory(Contest::class),
        'name' => $faker->unique()->jobTitle,
        'percentage' => $faker->numberBetween(1, 100),
    ];
});
