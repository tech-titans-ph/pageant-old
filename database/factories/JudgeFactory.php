<?php

// @var $factory \Illuminate\Database\Eloquent\Factory

use App\Contest;
use App\Judge;
use App\User;
use Faker\Generator as Faker;

$factory->define(Judge::class, function (Faker $faker) {
    return [
        'contest_id' => factory(Contest::class),
        'user_id' => factory(User::class)->states('judge'),
    ];
});
