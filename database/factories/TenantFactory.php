<?php

use Faker\Generator as Faker;

$factory->define(App\Tenant::class, function (Faker $faker) {
    $nric = $faker->myKadNumber();

    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'phone_number' => '+'.$faker->mobileNumber(true, false),
        'alt_phone_number' => '+'.$faker->mobileNumber(true, false),
        'id_value' => $nric,
        'idtype_id' => 1
    ];
});
