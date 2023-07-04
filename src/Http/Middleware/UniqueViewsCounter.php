<?php

namespace Sashagm\Analytics\Http\Middleware;

use Closure;
use Sashagm\Analytics\Traits\UniqueViewsCounterTrait;

class UniqueViewsCounter
{
    use UniqueViewsCounterTrait;

    public function handle($request, Closure $next)
    {
        $this->run($request);
        
        return $next($request);
    }
}
