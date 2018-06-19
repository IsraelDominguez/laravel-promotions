<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRewardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('promo_rewards', function (Blueprint $table) {
            $table->string('key',50)->unique();
            $table->string('name',100);
            $table->integer('stock')->default(0);
            $table->unsignedInteger('promo_id');

            $table->foreign('promo_id')->references('id')->on('promo');

            $table->primary('key');

        });

        Schema::create('promo_rewards_participations', function (Blueprint $table) {
            $table->unsignedInteger('participation_id');
            $table->string('key',50);
            $table->integer('amount')->default(1);

            $table->foreign('participation_id')->references('id')->on('promo_participations');
            $table->foreign('key')->references('key')->on('promo_rewards');

            $table->primary(['participation_id', 'key']);
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promo_rewards_participations');
        Schema::dropIfExists('promo_rewards');
    }
}
