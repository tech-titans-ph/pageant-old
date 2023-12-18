<?php

// @var $factory \Illuminate\Database\Eloquent\Factory

use App\Contest;
use Faker\Generator as Faker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

$factory->define(Contest::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->company,
        'description' => '$faker->catchPhrase',
        'logo' => Storage::put('logos', UploadedFile::fake()->image('logo.png')),
    ];
});
