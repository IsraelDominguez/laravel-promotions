<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('promo_type', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 250)->unique();
            $table->string('code', 250)->unique();
        });

        Schema::create('promo', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('campaign_id');
            $table->string('name', 250)->unique();
            $table->string('description', 1000)->nullable();
            $table->timestamp('starts')->nullable();
            $table->timestamp('ends')->nullable();
            $table->integer('min_age')->unsigned()->nullable();
            $table->integer('max_age')->unsigned()->nullable();
            $table->integer('max_user_participations')->unsigned()->nullable();
            $table->integer('max_user_participations_by_day')->unsigned()->nullable();
            $table->unsignedInteger('type_id');
            $table->timestamps();

            $table->foreign('campaign_id')->references('id')->on('promo_campaign');
            $table->foreign('type_id')->references('id')->on('promo_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promo');
        Schema::dropIfExists('promo_type');
    }
}
