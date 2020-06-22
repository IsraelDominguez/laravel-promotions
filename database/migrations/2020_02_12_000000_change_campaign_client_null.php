<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeCampaignClientNull extends Migration
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
            $table->dropForeign('promo_campaign_client_id_foreign');
            $table->dropColumn('client_id');
        });

        Schema::table('promo_campaign', function($table) {
            $table->string('client_id', 20)->nullable();
            $table->foreign('client_id')->references('client_id')->on('druid_apps');
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

