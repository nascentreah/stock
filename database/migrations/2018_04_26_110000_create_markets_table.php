<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('markets', function (Blueprint $table) {
            // columns
            $table->increments('id');
            $table->string('code', 15)->unique();
            $table->string('name', 150);
            $table->tinyInteger('status');
            $table->time('trading_start');
            $table->time('trading_end');
            $table->string('timezone_code', 30);
            $table->string('timezone_name', 100);
            $table->string('url', 500);
            $table->char('country_code', 2);
            $table->timestamps();
            // indexes
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('markets');
    }
}
