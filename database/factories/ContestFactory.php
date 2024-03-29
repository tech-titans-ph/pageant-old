<?php

// @var $factory \Illuminate\Database\Eloquent\Factory

use App\Contest;
use Faker\Generator as Faker;
use Illuminate\Http\UploadedFile;

$factory->define(Contest::class, function (Faker $faker) {
    return [
        'name' => collect([$faker->title, $faker->state, $faker->year])->implode(' '),
        'description' => $faker->catchPhrase,
        'scoring_system' => $faker->randomKey(config('options.scoring_systems')),
        'logo' => uploadedLogo(),
    ];
})->afterCreating(Contest::class, function ($contest, $faker) {
    $uploadedLogo = uploadedLogo();

    $contest->update([
        'logo' => $uploadedLogo->store("{$contest->id}/logo"),
    ]);
});

function uploadedLogo()
{
    return UploadedFile::fake()->image('logo.jpg');
}
