<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePromoEntrypoint extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::table('promo', function (Blueprint $table) {
            $table->string('entrypoint_id', 200)->nullable();
            $table->foreign('entrypoint_id')->references('key')->on('entrypoints');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('promo', function($table) {
            $table->dropColumn('entrypoint_id');
        });
    }
}
