<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExtraFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('promo_extra_fields', function (Blueprint $table) {
            $table->string('key',50)->unique();
            $table->string('name',100);
            $table->unsignedInteger('promo_id');

            $table->foreign('promo_id')->references('id')->on('promo');

            $table->primary('key');

        });

        Schema::create('promo_extra_fields_participations', function (Blueprint $table) {
            $table->unsignedInteger('participation_id');
            $table->string('key',50);
            $table->text('value');

            $table->foreign('participation_id')->references('id')->on('promo_participations');
            $table->foreign('key')->references('key')->on('promo_extra_fields');

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
        Schema::dropIfExists('promo_extra_fields_participations');
        Schema::dropIfExists('promo_extra_fields');
    }
}
