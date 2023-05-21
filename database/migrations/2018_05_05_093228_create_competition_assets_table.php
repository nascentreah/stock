<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompetitionAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // assets that are allowed for a particular competition
        Schema::create('competition_assets', function (Blueprint $table) {
            // columns
            $table->increments('id');
            $table->integer('competition_id')->unsigned();
            $table->integer('asset_id')->unsigned();
            // foreign keys
            $table->foreign('competition_id')->references('id')->on('competitions')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('asset_id')->references('id')->on('assets')->onUpdate('cascade')->onDelete('cascade');
            // indexes
            $table->unique(['competition_id','asset_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('competition_assets');
    }
}
