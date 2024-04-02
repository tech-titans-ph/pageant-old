<?php

// @var $factory \Illuminate\Database\Eloquent\Factory

use App\{Contest, Contestant};
use Faker\Generator as Faker;
use Illuminate\Http\UploadedFile;

$factory->define(Contestant::class, function (Faker $faker) {
    return [
        'contest_id' => factory(Contest::class),
        'name' => $faker->firstName . ' ' . $faker->lastName,
        'alias' => $faker->city,
        'avatar' => uploadedAvatar(),
    ];
})->afterCreating(Contestant::class, function ($contestant, $faker) {
    $uploadedAvatar = uploadedAvatar();

    $contestant->update([
        'avatar' => $uploadedAvatar->store("{$contestant->contest()->first()->id}/contestants"),
    ]);
});

function uploadedAvatar()
{
    return UploadedFile::fake()->image('avatar.png');
}
