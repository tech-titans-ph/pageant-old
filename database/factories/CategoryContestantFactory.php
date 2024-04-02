<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\{Category, CategoryContestant, Contestant};
use Faker\Generator as Faker;

$factory->define(CategoryContestant::class, function (Faker $faker) {
    return [
        'category_id' => factory(Category::class),
        'contestant_id' => factory(Contestant::class),
    ];
});
