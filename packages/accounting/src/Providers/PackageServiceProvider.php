<?php

namespace Packages\Accounting\Providers;

use App\Models\User as ParentUser;
use Packages\Accounting\Models\User;
use Packages\Accounting\ViewComposers\Backend\DashboardComposer;
use Packages\Accounting\ViewComposers\Backend\SettingComposer;
use Illuminate\Support\Facades\View;
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
        $this->loadMigrationsFrom($packageBaseDir . 'database/migrations');
        $this->loadRoutesFrom($packageBaseDir . 'routes/web.php');
        $this->loadViewsFrom($packageBaseDir . 'resources/views', 'accounting');
        $this->loadTranslationsFrom($packageBaseDir . 'resources/lang', 'accounting');

        View::composer(
            'pages.backend.dashboard', DashboardComposer::class
        );

        View::composer(
            'pages.backend.settings', SettingComposer::class
        );

        // IMPORTANT to override default auth user model,
        // so that auth()->user() returns an instance of Packages\Accounting\Models\User rather than App\Models\User
        config(['auth.providers.users.model' => User::class]);
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
            $packageBaseDir . 'config/config.php', 'accounting'
        );

        // Bind Packages\Accounting\Models\User to App\Models\User
        $this->app->bind(
            ParentUser::class,
            User::class
        );
    }
}
