<?php

namespace  Sashagm\Analytics\Traits;


use Exception;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Sashagm\Analytics\Http\Middleware\UniqueViewsCounter;
use Sashagm\Analytics\Http\Middleware\UniqueVisitorsCounter;


trait BootTrait
{
    public function starting()
    {
        $this->app['router']->aliasMiddleware('unique.views', UniqueViewsCounter::class);
        $this->app['router']->aliasMiddleware('unique.visitors', UniqueVisitorsCounter::class);

    }

}

