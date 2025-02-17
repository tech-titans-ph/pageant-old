<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
 */

$factory->define(User::class, function (Faker $faker) {
    $name = $faker->unique()->name;

    return [
        'name' => $name,
        'username' => Str::slug($name),
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
    ];
});

$factory->state(User::class, 'admin', []);

$factory->afterCreatingState(User::class, 'admin', function ($user) {
    $user->assign('admin');
});

$factory->state(User::class, 'judge', []);

$factory->afterCreatingState(User::class, 'judge', function ($user) {
    $user->assign('judge');
});
