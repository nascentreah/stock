<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\User;
use Packages\Accounting\Models\Account;

class AlterUsersTableDropAccountId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // add user_id column to accounts
        Schema::table('accounts', function (Blueprint $table) {
            $table->integer('user_id')->after('id')->unsigned();
        });

        // update foreign key to reference user table from accounts table
        User::where('account_id', '>', 0)->get()->each(function($user, $i) {
            // note that Account::find($user->account_id) will be treated as mass assignment,
            // so using mass update instead to avoid setting fillable / guarded model properties
            Account::where('id', $user->account_id)->update([
                'user_id' => $user->id
            ]);
        });

        // add foreign key constraint
        Schema::table('accounts', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });

        // drop foreign key from users to accounts and corresponding column
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['account_id']);
            $table->dropColumn('account_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // add account_id column
            $table->integer('account_id')->unsigned()->after('id')->nullable();
        });

        // update foreign key to reference user table from accounts table
        Account::where('user_id', '>', 0)->get()->each(function($account, $i) {
            User::where('id', $account->user_id)->update([
                'account_id' => $account->id
            ]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('account_id')->references('id')->on('accounts')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::table('accounts', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
}