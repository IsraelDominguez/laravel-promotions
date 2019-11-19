<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeDruidCampaign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::table('promo_campaign', function($table) {
            $table->dropColumn('secret');
            $table->dropColumn('client_id');
            $table->dropColumn('selflink');
        });

        Schema::table('promo_campaign', function($table) {
            $table->string('client_id', 20);
            $table->foreign('client_id')->references('client_id')->on('druid_apps');
        });

        Schema::table('promo', function($table) {
            $table->dropForeign('promo_entrypoint_id_foreign');
        });

        Schema::table('promo', function($table) {
            $table->dropColumn('entrypoint_id');
        });

        Schema::dropIfExists('promo_entrypoints');

        Schema::table('promo', function (Blueprint $table) {
            $table->string('entrypoint_id', 200)->nullable();
            // NO relation, entrypoints can be deleted from cockpit
            // $table->foreign('entrypoint_id')->references('key')->on('entrypoints');
        });

    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
    }
}

