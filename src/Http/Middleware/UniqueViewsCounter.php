<?php

namespace Sashagm\Analytics\Http\Middleware;


use Closure;
use Illuminate\Support\Facades\Log;
use Jenssegers\Agent\Facades\Agent;
use Illuminate\Support\Facades\Route;
use Sashagm\Analytics\Models\Visitor;
use Illuminate\Support\Facades\Cookie;
use Sashagm\Analytics\Models\Statistic;

class UniqueViewsCounter
{
    public function handle($request, Closure $next)
    {
        $isEnabled = config('analytics.enabled', true);
        $cookieLifetime = config('analytics.cookie_lifetime', 60 * 24 * 30); // 30 дней
        $savePeriod = config('analytics.save_period', 60 * 24 * 7); // 7 дней
        $ip = $_SERVER['REMOTE_ADDR'];

        // ...

        if ($isEnabled) {
            $routeName = $request->route()->getName();

            if ($routeName && strpos($routeName, 'admin.') !== 0) {
                $views = Cookie::get('views') ? json_decode(Cookie::get('views'), true) : [];

                if (!is_null($views) && !in_array($routeName, $views)) {
                    $views[] = $routeName;
                    Cookie::queue('views', json_encode($views), $cookieLifetime);

                    Visitor::create([
                        'category' => 'route',
                        'value' => $routeName,
                        'route' => Route::current()->uri(),
                        'ip_address' => $ip,
                    ]);

                    Log::info("Route {$routeName} visited by {$ip}");
                }
            }

            // сохраняем статистику раз в неделю
            if (time() % ($savePeriod * 60) == 0) {
                Statistic::create([
                    'category' => 'route',
                    'data' => json_encode($views),
                ]);
            }
        }


        // ...

        return $next($request);
    }

}
