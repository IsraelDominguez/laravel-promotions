<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddShareInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('promo_seo', function (Blueprint $table) {
            $table->string('text_share',300)->nullable()->comment('Text to share and page description');
            $table->string('image', 300)->nullable()->comment('Image for share and promo default');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('promo_seo', function($table) {
            $table->dropColumn('text_share');
            $table->dropColumn('image');
        });
    }
}
