<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromosSeoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('promo_seo', function (Blueprint $table) {
            $table->unsignedInteger('promo_id')->unique();
            $table->string('title',200);
            $table->string('facebook', 300)->nullable();
            $table->string('twitter', 300)->nullable();
            $table->string('whatsapp', 300)->nullable();

            $table->foreign('promo_id')->references('id')->on('promo');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promo_seo');
    }
}
