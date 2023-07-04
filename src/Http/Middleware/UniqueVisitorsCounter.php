<?php

namespace Sashagm\Analytics\Http\Middleware;


use Closure;
use Sashagm\Analytics\Traits\UniqueVisitorsCounterTrait;

class UniqueVisitorsCounter
{
    use UniqueVisitorsCounterTrait;

    public function handle($request, Closure $next)
    {
        $this->run($request);

        return $next($request);
    }
}