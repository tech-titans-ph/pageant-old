<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\{Bestin, Category, Contest, Criteria};
use Faker\Generator as Faker;

$factory->define(Bestin::class, function (Faker $faker) {
    $groupTypes = [
        'category' => Category::class,
        'criteria' => Criteria::class,
    ];

    return [
        'contest_id' => factory(Contest::class),
        'type' => $faker->randomElement(['category', 'criteria']),
        'type_id' => function (array $attributes) use ($groupTypes) {
            return factory($groupTypes[$attributes['type']]);
        },
        'name' => function (array $attributes) use ($groupTypes) {
            $model = app($groupTypes[$attributes['type']]);

            return $model->find($attributes['type_id'])->name;
        },
    ];
});
