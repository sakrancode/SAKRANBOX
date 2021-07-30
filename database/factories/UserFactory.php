<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\User;

// use Faker\Generator as Faker;
use Faker\Factory as Faker;

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

$factory->define(App\Models\User::class, function () {

    $faker = Faker::create('en_US');

    $genders = ['Male', 'Female'];
    $gender  = $faker->randomElement($genders);

    return [
        'co_id' => 1,
        'name' => $faker->name,
        'username' => $faker->userName,
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password / bcrypt('password')
        'gender'   => $gender,
        // 'phone'    => $faker->e164PhoneNumber,
        // 'level'    => 'O',
        'active'   => 'Y',

        // 'email' => $faker->unique()->safeEmail,
        // 'email_verified_at' => now(),

        'remember_token' => Str::random(10),
    ];
});
