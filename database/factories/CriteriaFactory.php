<?php

// @var $factory \Illuminate\Database\Eloquent\Factory

use App\{Category, Criteria};
use Faker\Generator as Faker;

$factory->define(Criteria::class, function (Faker $faker) {
    return [
        'category_id' => function () {
            return factory(Category::class)->create(['has_criterias' => true]);
        },
        'name' => $faker->unique(true)->randomElement([
            'Diction',
            'Content',
            'Personality & Presence, Confidence and walk',
            'Connection with Audience and Judges',
            'Poise & Posture',
            'Creativity',
            'Spelling, Punctuation, Grammar',
            'Response to Topic',
            'Originality',
            'Technique',
            'Presentation',
        ]),
        'max_points_percentage' => random_int(10, 100),
    ];
});
