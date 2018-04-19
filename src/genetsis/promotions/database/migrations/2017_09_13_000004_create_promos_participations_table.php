<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromosParticipationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('promo_participations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('promo_id');
            $table->string('user_id', 100);
            $table->timestamp('date')->useCurrent();
            $table->string('sponsor', 100)->nullable();
            $table->string('origin', 100)->nullable();
            $table->string('status', 30)->nullable();

            $table->foreign('promo_id')->references('id')->on('promo');
            $table->foreign('user_id')->references('id')->on('promo_users');
            $table->foreign('sponsor')->references('id')->on('promo_users');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promo_participations');
    }
}
