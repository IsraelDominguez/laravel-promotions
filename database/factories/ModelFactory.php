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

use Genetsis\Promotions\Models\Campaign;
use Genetsis\Promotions\Models\Promotion;
use Genetsis\Promotions\Models\PromoType;
use Genetsis\Promotions\Models\User;

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

$factory->define(\Genetsis\Promotions\Models\User::class, function (Faker\Generator $faker) {
    return [
        'id' => $faker->sha1(),
        'email' => $faker->email(),
        'name' => $faker->name,
        'sponsor_code' => $faker->ean8()
    ];
});

$factory->define(\Genetsis\Promotions\Models\Participation::class,  function (Faker\Generator $faker) {
    return [
        'user_id' => User::all()->random()->id,
        'date' =>  $faker->dateTimeBetween($startDate = '-10 days', $endDate = 'now'),
    ];
});


$factory->define(\Genetsis\Promotions\Models\Promotion::class, function (Faker\Generator $faker) {
    return [
        'starts' => $faker->dateTimeBetween($startDate = '-30 days', $endDate = 'now'),
        'ends' => $faker->dateTimeBetween($startDate = 'now', $endDate = '+30 days'),
        'max_user_participations' => 1,
        'key' => $faker->slug(3),
        'has_mgm' => $faker->boolean,
        'campaign_id' => Campaign::all()->random()->id
    ];
});

$factory->state(Promotion::class, 'sorteo', function(Faker\Generator $faker) {
    return [
        'name' => 'Promotion Sorteo: ' . $faker->slug(3),
        'type_id' => 1
    ];
});
