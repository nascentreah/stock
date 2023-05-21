<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AlterDepositsTableChangePaymentCurrency extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // add payment_currency_code (to replace payment_currency_id)
        Schema::table('deposits', function (Blueprint $table) {
            $table->decimal('payment_amount', 22, 8)->change(); // increase decimal precision, requires doctrine/dbal
            $table->decimal('payment_fx_rate', 18, 8)->change(); // increase decimal precision, requires doctrine/dbal
            $table->string('payment_currency_code', 30)->after('payment_currency_id');
        });

        // update code from payment_currency_id relationship
        DB::table('deposits')
            ->update(['payment_currency_code' => DB::raw('(SELECT code FROM currencies WHERE id = deposits.payment_currency_id)')]);

        // drop payment_currency_id as it's no longer needed
        Schema::table('deposits', function (Blueprint $table) {
            $table->dropForeign(['payment_currency_id']);
            $table->dropColumn('payment_currency_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('deposits', function (Blueprint $table) {
            $table->decimal('payment_amount', 16, 2)->change();
            $table->decimal('payment_fx_rate', 16, 6)->change();
            $table->dropColumn('payment_currency_code');
            $table->integer('payment_currency_id')->unsigned();
            $table->foreign('payment_currency_id')->references('id')->on('currencies')->onUpdate('cascade')->onDelete('cascade');
        });
    }
}
