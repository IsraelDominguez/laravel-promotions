<?php namespace Genetsis\Promotions\Seeds;

use Genetsis\Promotions\Models\Moment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PromotionTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('promo_type')->insert([
            [ 'id' => 1, 'name' => 'Sorteo', 'code' => 'sorteo' ],
            //[ 'id' => 2, 'name' => 'Pincode', 'code' => 'pincode' ],
            //[ 'id' => 3, 'name' => 'Win Moment', 'code' => 'win-moment' ]
        ]);

    }
}
