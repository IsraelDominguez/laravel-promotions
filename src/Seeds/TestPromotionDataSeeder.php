<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestPromotionDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker\Generator $faker)
    {
        factory(\Genetsis\Promotions\Models\Campaign::class, 3)->create();
    }
}
