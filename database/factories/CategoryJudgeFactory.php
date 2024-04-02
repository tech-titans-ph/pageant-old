<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\{Category, CategoryJudge, Judge};
use Faker\Generator as Faker;

$factory->define(CategoryJudge::class, function (Faker $faker) {
    return [
        'category_id' => factory(Category::class),
        'judge_id' => factory(Judge::class),
    ];
});
