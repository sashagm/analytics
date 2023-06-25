<?php

namespace Sashagm\Analytics\Http\Middleware;


use Closure;
use Sashagm\Analytics\Models\Visitor;
use Illuminate\Support\Facades\Cookie;
use Sashagm\Analytics\Traits\UniqueVisitorsCounterTrait;

class UniqueVisitorsCounter
{

    use UniqueVisitorsCounterTrait;


    public function handle($request, Closure $next)
    {
        

        $this->starting($request);

        return $next($request);
    }
}