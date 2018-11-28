<?php namespace Genetsis\Promotions\Seeds;

use Genetsis\Promotions\Models\Moment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QrTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('promo_type')->insert([
            [ 'id' => 4, 'name' => 'Qrs', 'code' => 'qrs' ]
        ]);
    }
}
