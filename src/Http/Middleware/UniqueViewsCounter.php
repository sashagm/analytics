<?php

namespace Sashagm\Analytics\Http\Middleware;


use Closure;
use Illuminate\Support\Facades\Route;
use Sashagm\Analytics\Models\Visitor;
use Illuminate\Support\Facades\Cookie;

class UniqueViewsCounter
{
    public function handle($request, Closure $next)
    {
        $isEnabled = config('analytics.enabled', true);



        $ip = $_SERVER['REMOTE_ADDR'];



        if ($isEnabled) {
            $routeName = $request->route()->getName();

            if ($routeName && strpos($routeName, 'admin.') !== 0) {
                $views = Cookie::get('views') ? json_decode(Cookie::get('views'), true) : [];

                if (!is_null($views) && !in_array($routeName, $views)) {
                    $views[] = $routeName;
                    Cookie::queue('views', json_encode($views), 60 * 24 * 30);

                    Visitor::create([
                        'category' => 'route',
                        'value' => $routeName,
                        'route' => Route::current()->uri(),
                        'ip_address' => $ip,
                    ]);
                }
            }
        }

        return $next($request);
    }
}
