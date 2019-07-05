<?php

use Faker\Generator as Faker;

$factory->define(App\Beneficiary::class, function (Faker $faker) {

    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'phone_number' => '+'.$faker->mobileNumber(true, false),
        'alt_phone_number' => '+'.$faker->mobileNumber(true, false),
        'id_value' => $faker->myKadNumber(),
        'idtype_id' => 1,
        'address' => $faker->address,
        'city' => $faker->township,
        'state' => $faker->state,
        'postcode' => $faker->postcode,
        'occupation' => $faker->sentence,
        'invest_property_num' => $faker->randomDigit,
        'remarks' => $faker->paragraph(),
        'gender_id' => $faker->numberBetween(1, 3),
        'nationality_id' => 1,
        'race_id' => $faker->numberBetween(1, 3),
    ];
});
