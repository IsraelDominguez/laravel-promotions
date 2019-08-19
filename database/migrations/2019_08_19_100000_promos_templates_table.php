<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PromosTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('promo_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('promo_id');
            $table->string('page',30)->comment('Page that the content is store');
            $table->string('template', 20)->comment('Template used in this page');
            $table->text('content')->comment('json with all fields for the template');

            $table->foreign('promo_id')->references('id')->on('promo');
        });

        Schema::create('promo_design', function (Blueprint $table) {
            $table->unsignedInteger('promo_id')->unique();
            $table->text('background_image')->nullable()->comment('base64 image for the background');
            $table->string('background_color', 7)->nullable()->comment('Hex color');

            $table->foreign('promo_id')->references('id')->on('promo');
        });

        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `promo_design` CHANGE `background_image` `background_image` LONGTEXT NULL DEFAULT NULL COMMENT 'base64 image';");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promo_templates');
        Schema::dropIfExists('promo_design');
    }
}
