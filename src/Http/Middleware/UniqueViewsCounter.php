<?php

namespace Sashagm\Analytics\Http\Middleware;


use Closure;
use Illuminate\Support\Facades\Log;
use Jenssegers\Agent\Facades\Agent;
use Illuminate\Support\Facades\Route;
use Sashagm\Analytics\Models\Visitor;
use Illuminate\Support\Facades\Cookie;
use Sashagm\Analytics\Models\Statistic;
use Sashagm\Analytics\Traits\UniqueViewsCounterTrait;

class UniqueViewsCounter
{

    use UniqueViewsCounterTrait;

    public function handle($request, Closure $next)
    {

        $this->start($request);
        return $next($request);
    }
}
