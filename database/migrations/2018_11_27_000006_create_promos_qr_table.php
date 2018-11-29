<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromosQrTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('promo_packs_qrs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('promo_id');
            $table->string('pack', 100);
            $table->string('key', 100)->nullable();
            $table->string('name', 100)->nullable();
            $table->integer('max')->nullable();

            $table->foreign('promo_id')->references('id')->on('promo');
        });

        Schema::create('promo_participation_qrs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('participation_id')->nullable();
            $table->string('object_id',100)->nullable();

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
        Schema::dropIfExists('promo_qrs');
        Schema::dropIfExists('promo_participation_qrs');
    }
}
