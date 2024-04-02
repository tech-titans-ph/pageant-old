<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\{Category,  Contestant, Criteria, Judge, Score};
use Faker\Generator as Faker;

$factory->define(Score::class, function (Faker $faker) {
    $criteria = factory(Criteria::class)->create();

    $category = $criteria->category;

    $contest = $category->contest;

    $judge = factory(Judge::class)->create(['contest_id' => $contest->id]);

    $contestant = factory(Contestant::class)->create(['contest_id' => $contest->id]);

    $categoryJudge = $category->judges()->attach($judge->id);

    $categoryContestant = $category->contestants()->attach($contestant->id);

    return [
        'category_id' => $category->id,
        'criteria_id' => $criteria->id,
        'category_contestant_id' => $category->contestants()->first()->pivot->id,
        'category_judge_id' => $category->judges()->first()->pivot->id,
        'points' => function (array $attributes) {
            if ($attributes['criteria_id']) {
                return random_int(10, Criteria::find($attributes['criteria_id'])->max_points_percentage);
            }

            return random_int(10, Category::find($attributes['category_id'])->max_points_percentage);
        },
    ];
});
