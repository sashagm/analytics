<?php

namespace Sashagm\Analytics\Providers;

use Exception;
use Illuminate\Support\ServiceProvider;
use Sashagm\Analytics\Console\Commands\InstallCommand;
use Sashagm\Analytics\Traits\BootTrait;

class AnalyticsServiceProvider extends ServiceProvider
{

    use BootTrait;

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */


    public function boot()
    {

        $this->publishFiles();

        $this->registerRouter();

        $this->registerMigrate();

        $this->registerCommands();

        $this->starting();
    }


    protected function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
            ]);
        } else {
            $this->commands([
                InstallCommand::class,
            ]);
        }
    }

    protected function registerRouter()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/analytics.php');
    }

    protected function registerMigrate()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    protected function publishFiles()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/analytics.php',
            'analytics'
        );
    }
}
