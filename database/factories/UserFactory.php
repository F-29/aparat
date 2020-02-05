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
    return [
        'name' => $faker->name,
        'type' => User::TYPE_USER,
        'email' => $faker->unique()->safeEmail,
        'password' => bcrypt('13791379'),
        'mobile' => "+98" . random_int(1111, 9999) . random_int(11111, 99999),
        'avatar' => null,
        'website' => $faker->url,
        'verify_code' => null,
        'verified_at' => now()
    ];
});
