<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

$factory->define(\Genetsis\Promotions\Models\Participation::class,  function (Faker\Generator $faker) {
    return [
        'user_id' => \Genetsis\Promotions\Models\User::inRandomOrder()->first()->id,
        'date' =>  $faker->dateTimeBetween($startDate = '-10 days', $endDate = 'now'),
        'sponsor' => $faker->optional($weight = 0.2)->passthrough(\Genetsis\Promotions\Models\User::inRandomOrder()->first()->id)
    ];
});

