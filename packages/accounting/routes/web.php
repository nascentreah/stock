<?php

// Frontend routes
Route::name('frontend.')
    ->namespace('Packages\Accounting\Http\Controllers\Frontend')
    ->middleware('web', 'auth', 'cookie-consent', 'email_verified') // it's important to add web middleware group for authentication to work
    ->group(function () {
        Route::get('users/{user}/account', 'AccountController@show')->name('account.show');
        // deposits
        Route::get('users/{user}/deposits', 'DepositController@index')->name('deposits.index');
        Route::get('users/{user}/deposits/create/{payment_method}', 'DepositController@create')->name('deposits.create');
        Route::post('users/{user}/deposits/create/{payment_method}', 'DepositController@store')->name('deposits.store');
        Route::get('users/{user}/deposits/complete/{payment_method}', 'DepositController@complete')->name('deposits.complete');
        // withdrawals
        Route::get('users/{user}/withdrawals', 'WithdrawalController@index')->name('withdrawals.index');
        Route::get('users/{user}/withdrawals/create/{withdrawal_method}', 'WithdrawalController@create')->name('withdrawals.create');
        Route::post('users/{user}/withdrawals/create/{withdrawal_method}', 'WithdrawalController@store')->name('withdrawals.store');
    });

// Backend routes
Route::prefix('admin')
    ->name('backend.')
    ->namespace('Packages\Accounting\Http\Controllers\Backend')
    ->middleware('web', 'auth', 'email_verified', 'role:' . App\Models\User::ROLE_ADMIN) // it's important to add web middleware group for authentication to work
    ->group(function () {
        Route::resource('accounts', 'AccountController',  ['only' => ['index']]);
        Route::resource('deposits', 'DepositController',  ['only' => ['index']]);
        Route::resource('withdrawals', 'WithdrawalController',  ['only' => ['index','edit','update']]);
    });

// Web hooks
Route::name('webhook.')
    ->namespace('Packages\Accounting\Http\Controllers\Frontend')
    ->group(function () {
        Route::post('deposits/event', 'DepositController@event')->name('deposits.event');
    });