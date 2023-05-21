<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assets', function (Blueprint $table) {
            // columns
            $table->increments('id');
            $table->integer('market_id')->unsigned();
            $table->integer('currency_id')->unsigned();
            $table->string('symbol', 30);
            $table->string('symbol_ext', 50)->unique();
            $table->string('name', 150);
            $table->tinyInteger('status');
            $table->string('logo', 100)->nullable();
            $table->decimal('price', 20, 8)->default(0);
            $table->decimal('change_abs', 20, 8)->default(0);
            $table->decimal('change_pct', 12, 2)->default(0);
            $table->bigInteger('volume')->default(0);
            $table->bigInteger('supply')->default(0);
            $table->bigInteger('market_cap')->default(0);
            $table->timestamps();
            // foreign keys
            $table->foreign('market_id')->references('id')->on('markets')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('currency_id')->references('id')->on('currencies')->onUpdate('cascade')->onDelete('cascade');
            // indexes
            $table->unique(['market_id','symbol']);
            $table->index('symbol');
            $table->index('name');
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
        Schema::dropIfExists('assets');
    }
}
