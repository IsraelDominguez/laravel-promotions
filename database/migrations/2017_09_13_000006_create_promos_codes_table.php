<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromosCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('promo_codes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('promo_id');
            $table->unsignedInteger('participation_id')->nullable();
            $table->timestamp('used')->nullable();
            $table->string('code',100)->nullable();
            $table->timestamp('expires')->nullable();

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
        Schema::dropIfExists('promo_codes');
    }
}
