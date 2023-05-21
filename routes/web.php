<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Auth routes
Route::group(['middleware' => 'cookie-consent'], function () {
    Auth::routes(['verify' => config('settings.users.email_verification')]);
});

// Social login
Route::prefix('login')
    ->name('login.')
    ->namespace('Auth')
    ->middleware('guest','social')
    ->group(function () {
        Route::get('{provider}', 'SocialLoginController@redirect');
        Route::get('{provider}/callback', 'SocialLoginController@сallback');
});


// Frontend routes (public)
Route::name('frontend.')
    ->namespace('Frontend')
    ->middleware('cookie-consent')
    ->group(function () {
        Route::get('/', 'PageController@index')->name('index');
        Route::get('page/{slug}', 'PageController@display');
        Route::post('cookie/accept', 'PageController@acceptCookies');
});

// Frontend routes (logged in users)
Route::name('frontend.')
    ->namespace('Frontend')
    ->middleware('auth','active','email_verified','cookie-consent')
    ->group(function () {
        Route::get('dashboard', 'DashboardController@index')->name('dashboard');
        Route::resource('users', 'UserController',  ['only' => ['show','edit','update']]);
        Route::resource('competitions', 'CompetitionController', ['only' => ['index','show']]);
        Route::post('competitions/{competition}/join', 'CompetitionController@join')->name('competitions.join');
        Route::post('competitions/{competition}/assets/{asset}/trade', 'CompetitionController@openTrade')->name('competitions.trade.open');
        Route::get('competitions/{competition}/assets/search/{query}', 'CompetitionController@searchAsset')->name('competitions.assets.search');
        Route::post('competitions/{competition}/trades/{trade}/close', 'CompetitionController@closeTrade')->name('competitions.trade.close');
        Route::get('competitions/{competition}/history', 'CompetitionController@history')->name('competitions.history');
        Route::get('competitions/{competition}/leaderboard', 'CompetitionController@leaderboard')->name('competitions.leaderboard');
        Route::get('competitions/{competition}/trades', 'CompetitionController@trades')->name('competitions.trades');
        Route::get('competitions/{competition}/participants', 'CompetitionController@participants')->name('competitions.participants');
        Route::get('assets', 'AssetController@index')->name('assets.index');
        Route::get('assets/search/{query}', 'AssetController@search')->name('assets.search');
        Route::post('assets/info', 'AssetController@info')->name('assets.info');
        Route::get('assets/{asset}/history/{range}', 'AssetController@history')->name('assets.history');
        Route::get('rankings', 'RankingController@index')->name('rankings');
        // chat
        Route::get('chat', 'ChatMessageController@index')->name('chat.index');
        Route::get('chat/messages/get', 'ChatMessageController@getMessages')->name('chat.messages.get');
        Route::post('chat/messages/send', 'ChatMessageController@sendMessage')->name('chat.messages.send');
        // locale select
        Route::post('locale/{locale}/remember', 'LocaleController@remember')->name('locale.remember');
        Route::get('help', 'PageController@help')->name('help');
});

// Pass some config variables and translations strings to the client side via variables.js
// read more: https://medium.com/@serhii.matrunchyk/using-laravel-localization-with-javascript-and-vuejs-23064d0c210e
Route::get('js/variables.js', function () {
    $variables = Cache::rememberForever('variables.js', function () {
        $strings = require resource_path('lang/' . config('app.locale') . '/app.php');
        $config = [
            'color'                             => config('settings.color'),
            'number_decimal_point'                      => config('settings.number_decimal_point'),
            'number_thousands_separator'                => config('settings.number_thousands_separator'),
            'competitions_assets_quotes_update_freq'    => config('settings.competitions_assets_quotes_update_freq'),
        ];

        $broadcasting = [
            'connections' => [
                'pusher' => [
                    'key' => config('broadcasting.connections.pusher.key'),
                    'options' => [
                        'cluster' => config('broadcasting.connections.pusher.options.cluster')
                    ]
                ]
            ]
        ];

        return 'var cfg = ' . json_encode(['settings' => $config, 'broadcasting' => $broadcasting]) . '; var i18n = ' . json_encode(['app' => $strings]) . ';';
    });

    return response($variables)->header('Content-Type', 'text/javascript');
})->name('assets.i18n');

// Backend routes
Route::prefix('admin')
    ->name('backend.')
    ->namespace('Backend')
    ->middleware('auth','active','email_verified','role:' . App\Models\User::ROLE_ADMIN)
    ->group(function () {
        // admin dashoard
        Route::get('/', 'DashboardController@index')->name('dashboard');
        // assets management
        Route::resource('assets', 'AssetController', ['except' => ['show']]);
        Route::get('assets/{asset}/delete', 'AssetController@delete')->name('assets.delete');
        // users management
        Route::resource('users', 'UserController',  ['except' => ['create','store','show']]);
        Route::get('users/{user}/delete', 'UserController@delete')->name('users.delete');
        Route::post('users/generate', 'UserController@generate')->name('users.generate');
        // Account management
        Route::get('admincredit', 'UserController@credit')->name('admincredit.index');
        // competitions management
        Route::resource('competitions', 'CompetitionController',  ['except' => ['show']]);
        Route::get('competitions/{competition}/delete', 'CompetitionController@delete')->name('competitions.delete');
        Route::get('competitions/{competition}/clone', 'CompetitionController@duplicate')->name('competitions.clone');
        Route::get('competitions/{competition}/bots/add', 'CompetitionController@addBotsForm')->name('competitions.bots.add');
        Route::post('competitions/{competition}/bots/add', 'CompetitionController@addBots')->name('competitions.bots.add');
        Route::get('competitions/{competition}/bots/remove', 'CompetitionController@removeBotsForm')->name('competitions.bots.remove');
        Route::post('competitions/{competition}/bots/remove', 'CompetitionController@removeBots')->name('competitions.bots.remove');
        // trades management
        Route::resource('trades', 'TradeController',  ['only' => ['index','edit']]);
        // add-ons
        Route::get('add-ons', 'AddonController@index')->name('addons.index');
        // settings
        Route::get('settings', 'SettingController@index')->name('settings.index');
        Route::post('settings', 'SettingController@update')->name('settings.update');

        // maintenance
        Route::get('maintenance', 'MaintenanceController@index')->name('maintenance.index');
        Route::post('maintenance/cache/clear', 'MaintenanceController@cache')->name('maintenance.cache');
        Route::post('maintenance/migrate', 'MaintenanceController@migrate')->name('maintenance.migrate');
        Route::post('maintenance/cron', 'MaintenanceController@cron')->name('maintenance.cron');
        Route::post('maintenance/cron/assets-market-data', 'MaintenanceController@cronAssetsMarketData')->name('maintenance.cron_assets_market_data');
        Route::post('maintenance/cron/currencies-market-data', 'MaintenanceController@cronCurrenciesMarketData')->name('maintenance.cron_currencies_market_data');
        Route::get('license', 'LicenseController@index')->name('license.index');
        Route::post('license', 'LicenseController@register')->name('license.register');
});
