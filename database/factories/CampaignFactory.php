<?php

use Faker\Generator as Faker;

$factory->define(\Genetsis\Promotions\Models\Campaign::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'starts' => $faker->dateTimeBetween($startDate = '-30 days', $endDate = 'now'),
        'ends' => $faker->dateTimeBetween($startDate = 'now', $endDate = '+30 days'),
        'key' => $faker->slug(3),
        'client_id' => \Genetsis\Admin\Models\DruidApp::inRandomOrder()->first()->client_id
    ];
});
