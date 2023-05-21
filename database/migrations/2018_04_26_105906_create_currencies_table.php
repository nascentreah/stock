<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currencies', function (Blueprint $table) {
            // columns
            $table->increments('id');
            $table->string('code', 3)->unique();
            $table->string('name')->unique();
            $table->string('symbol_native', 50);
            $table->decimal('rate', 12, 4)->default(0); // rate between given currency and website currency
            $table->decimal('fraction', 10, 6)->default(1);
            $table->timestamps();
            // indexes
            $table->index('fraction');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('currencies');
    }
}
