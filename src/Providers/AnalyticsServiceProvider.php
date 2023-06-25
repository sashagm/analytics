<?php

namespace Sashagm\Analytics\Providers;

use Exception;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Sashagm\Analytics\Console\Commands\InstallCommand;
use Sashagm\Analytics\Http\Middleware\UniqueViewsCounter;
use Sashagm\Analytics\Http\Middleware\UniqueVisitorsCounter;


class AnalyticsServiceProvider extends ServiceProvider
{



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


        $this->loadRoutesFrom(__DIR__.'/../routes/analytics.php');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->mergeConfigFrom(
            __DIR__.'/../config/analytics.php', 'analytics'
        );
        
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
            ]);
        }

        $this->app['router']->aliasMiddleware('unique.views', UniqueViewsCounter::class);
        $this->app['router']->aliasMiddleware('unique.visitors', UniqueVisitorsCounter::class);


        



    }




     
}
