<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntrypointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('promo_entrypoints', function (Blueprint $table) {
            $table->string('key', 200)->unique();
            $table->string('name', 200);
            $table->unsignedInteger('campaign_id');
            $table->text('ids')->comment('Json with ids fields');
            $table->text('fields')->comment('Json with data fields');
            $table->timestamp('deleted_at')->nullable();

            $table->foreign('campaign_id')->references('id')->on('promo_campaign');
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
        Schema::dropIfExists('entrypoints');
    }
}
