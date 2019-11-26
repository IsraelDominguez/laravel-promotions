<?php


$factory->define(\Genetsis\Promotions\Models\User::class, function (Faker\Generator $faker) {
    return [
        'id' => $faker->sha1(),
        'email' => $faker->email(),
        'name' => $faker->name,
        'sponsor_code' => $faker->ean8()
    ];
});
