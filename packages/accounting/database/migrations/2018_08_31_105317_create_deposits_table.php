<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepositsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deposits', function (Blueprint $table) {
            // columns
            $table->increments('id');
            $table->integer('account_id')->unsigned();
            $table->integer('payment_method_id')->unsigned();
            $table->decimal('payment_amount', 16, 2);
            $table->integer('payment_currency_id')->unsigned();
            $table->decimal('payment_fx_rate', 16, 6)->nullable();
            $table->decimal('amount', 16, 2)->nullable();
            $table->tinyInteger('status');
            $table->string('external_id', 100);
            $table->timestamps();
            // foreign keys
            $table->foreign('account_id')->references('id')->on('accounts')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('payment_method_id')->references('id')->on('payment_methods')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('payment_currency_id')->references('id')->on('currencies')->onUpdate('cascade')->onDelete('cascade');
            // indexes
            $table->index('external_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deposits');
    }
}
