<?php

namespace Packages\CashCompetitions\Providers;

use App\Models\Competition as ParentCompetition;
use App\Http\Requests\Frontend\JoinCompetition as ParentJoinCompetition;
use Packages\CashCompetitions\Http\Requests\Frontend\JoinCompetition;
use Packages\CashCompetitions\Models\Competition;
use Illuminate\Support\ServiceProvider;

class PackageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $packageBaseDir = __DIR__ . '/../../';
        $this->loadViewsFrom($packageBaseDir . 'resources/views', 'cash-competitions');
        $this->loadTranslationsFrom($packageBaseDir . 'resources/lang', 'cash-competitions');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $packageBaseDir = __DIR__ . '/../../';
        // load package config
        $this->mergeConfigFrom(
            $packageBaseDir . 'config/config.php', 'cash-competitions'
        );

      // Bind Packages\CashCompetitions\Models\Competition to App\Models\Competition
        $this->app->bind(
            ParentCompetition::class,
            Competition::class
        );

        // override JoinCompetition rules
        $this->app->bind(
            ParentJoinCompetition::class,
            JoinCompetition::class
        );
    }
}
