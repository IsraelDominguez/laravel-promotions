<?php


$factory->define(\Genetsis\Promotions\Models\Promotion::class, function (Faker\Generator $faker) {
    return [
        'starts' => $faker->dateTimeBetween($startDate = '-30 days', $endDate = 'now'),
        'ends' => $faker->dateTimeBetween($startDate = 'now', $endDate = '+30 days'),
        'max_user_participations' => 1,
        'key' => $faker->slug(3),
        'has_mgm' => $faker->boolean,
        'lang' => 'es',
        'campaign_id' => \Genetsis\Promotions\Models\Campaign::inRandomOrder()->first()->id
    ];
});

$factory->state(\Genetsis\Promotions\Models\Promotion::class, 'sorteo', function(Faker\Generator $faker) {
    return [
        'name' => 'Promotion Sorteo: ' . $faker->slug(2),
        'type_id' => 1
    ];
});

$factory->state(\Genetsis\Promotions\Models\Promotion::class, 'pincode', function(Faker\Generator $faker) {
    return [
        'name' => 'Promotion Pincode: ' . $faker->slug(2),
        'type_id' => 2
    ];
});

$factory->define(\Genetsis\Promotions\Models\Moment::class, function (Faker\Generator $faker) {
    return [
        'date' => $faker->dateTimeBetween($startDate = 'now', $endDate = '+10 days'),
        'code_to_send' => $faker->ean8()
    ];
});

$factory->define(\Genetsis\Promotions\Models\Codes::class, function (Faker\Generator $faker) {
    return [
        'code' => $faker->ean8(),
        'expires' => $faker->dateTimeBetween($startDate = '+30', $endDate = '+50 days'),
    ];
});

$factory->state(\Genetsis\Promotions\Models\Promotion::class, 'multipleParticipations', function(Faker\Generator $faker) {
    return [
        'max_user_participations' => $faker->randomDigit
    ];
});
