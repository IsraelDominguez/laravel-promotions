<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromosMomentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('promo_moments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('promo_id');
            $table->unsignedInteger('participation_id')->nullable();
            $table->timestamp('date')->nullable();
            $table->timestamp('used')->nullable();
            $table->string('code_to_send',100)->nullable();
            $table->timestamp('code_send')->nullable();

            $table->foreign('promo_id')->references('id')->on('promo');
            $table->foreign('participation_id')->references('id')->on('promo_participations');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promo_moments');
    }
}
