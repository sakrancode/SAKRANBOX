<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Company;

use Faker\Factory as Faker;

$factory->define(App\Models\Company::class, function () {
    $faker = Faker::create('en_US');

    return [
        'name' => $faker->company,
        'npwp' => Faker::create('id_ID')->nik,
        'address' => $faker->address,
        'city'   => $faker->city,
        'phone'    => $faker->e164PhoneNumber,
        'active'   => 'Y',
    ];
});
