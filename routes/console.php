<?php

use App\Helpers\PackageManager;
use App\Services\DotEnvService;
use App\Services\LicenseService;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('validate', function (PackageManager $pm, LicenseService $ls, DotEnvService $dotenv) {
    set_time_limit(1800);

    if (!$ls->register(env('PURCHASE_CODE'), env('LICENSEE_EMAIL'))->success) {
        $env = $dotenv->load();
        $env = array_merge($env, collect(['PURCHASE_CODE', 'SECURITY_HASH', 'LICENSEE_EMAIL'])->mapWithKeys(function ($v) { return [$v => NULL]; })->toArray());
        $dotenv->save($env);
    }
});
