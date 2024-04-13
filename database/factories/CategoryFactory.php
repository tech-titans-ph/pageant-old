<?php

// @var $factory \Illuminate\Database\Eloquent\Factory

use App\{Category, Contest};
use Faker\Generator as Faker;

$factory->define(Category::class, function (Faker $faker) {
    return [
        'contest_id' => factory(Contest::class),
        'name' => $faker->unique(true)->randomElement(['Introduction', 'Evening Wear', 'Question and Answer', 'Essay', 'Pre-Pageant Interview', 'Talent']),
        'status' => $faker->randomKey(config('options.category_statuses')),
        'has_criterias' => $faker->boolean(),
        'scoring_system' => function (array $attributes) use ($faker) {
            if ($attributes['has_criterias']) {
                return Contest::find($attributes['contest_id'])->scoring_system == 'average'
                    ? 'average'
                    : $faker->randomKey(config('options.scoring_systems'));
            }
        },
        'max_points_percentage' => function (array $attributes) {
            return $attributes['has_criterias'] && ($attributes['scoring_system'] == 'ranking' || Contest::find($attributes['contest_id'])->scoring_system == 'ranking')
                ? null
                : random_int(10, 100);
        },
        'step' => function (array $attributes) {
            return $attributes['has_criterias']
                ? fake()->randomElement([0.2, 0.25, 0.5, 1])
                : null;
        },
    ];
});
