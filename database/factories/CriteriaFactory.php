<?php

// @var $factory \Illuminate\Database\Eloquent\Factory

use App\Category;
use App\Criteria;
use Faker\Generator as Faker;

$factory->define(Criteria::class, function (Faker $faker) {
    return [
        'category_id' => factory(Category::class),
        'name' => $faker->unique()->jobTitle,
        'percentage' => $faker->numberBetween(1, 100),
    ];
});
