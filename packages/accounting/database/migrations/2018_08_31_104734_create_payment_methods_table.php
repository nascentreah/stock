<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->integer('payment_gateway_id')->unsigned();
            $table->string('code', 30)->unique();
            $table->tinyInteger('status');
            $table->timestamps();
            // foreign keys
            $table->foreign('payment_gateway_id')->references('id')->on('payment_gateways')->onUpdate('cascade')->onDelete('cascade');
            // indexes
            $table->primary('id');
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
        Schema::dropIfExists('payment_methods');
    }
}
