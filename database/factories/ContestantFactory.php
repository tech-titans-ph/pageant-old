<?php

// @var $factory \Illuminate\Database\Eloquent\Factory

use App\Contest;
use App\Contestant;
use Faker\Generator as Faker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

$factory->define(Contestant::class, function (Faker $faker) {
    return [
        'contest_id' => factory(Contest::class),
        'name' => $faker->name,
        'description' => $faker->state,
        'number' => 1,
        'picture' => Storage::put('profile-pictures', UploadedFile::fake()->image('avatar.png')),
    ];
});
