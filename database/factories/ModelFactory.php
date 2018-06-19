<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(\Genetsis\Promotions\Models\Moment::class, function (Faker\Generator $faker) {
    return [
        'promo_id' => 1,
        'date' => $faker->dateTimeBetween($startDate = 'now', $endDate = '+10 days'),
        'code_to_send' => $faker->ean8()
    ];
});

$factory->define(\Genetsis\Promotions\Models\Codes::class, function (Faker\Generator $faker) {
    return [
        'promo_id' => 1,
        'code' => $faker->ean8()
    ];
});


$factory->define(\Genetsis\Promotions\Models\Participation::class, function (Faker\Generator $faker) {
    return [
        'user_id' => 'my-id-user',
        'promo_id' => 1,
        'date' =>  $faker->dateTimeBetween($startDate = '-5 days', $endDate = 'now'),
    ];
});
