<?php

namespace Sashagm\Analytics\Http\Middleware;


use Closure;
use Sashagm\Analytics\Models\Visitor;
use Illuminate\Support\Facades\Cookie;

class UniqueVisitorsCounter
{
    public function handle($request, Closure $next)
    {
        

$ip = $_SERVER['REMOTE_ADDR'];

        
        
        $visitorId = Cookie::get('visitor_id');

        if (!$visitorId) {
            $visitorId = uniqid();
            Cookie::queue('visitor_id', $visitorId, 60 * 24 * 30);

            Visitor::create([
                'category' => 'user',
                'value' => $visitorId,
                'ip_address' => $ip, 
            ]);
        }

        return $next($request);
    }
}