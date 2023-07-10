<?php

namespace  Sashagm\Analytics\Traits;

use Closure;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Sashagm\Analytics\Models\Visitor;
use Illuminate\Support\Facades\Cookie;
use Sashagm\Analytics\Models\Statistic;

trait UniqueViewsCounterTrait
{
    public function run($request)
    {
        $isEnabled = config('analytics.enabled', true);
        $cookieLifetime = config('analytics.cookie_lifetime', 60 * 24 * 30); // 30 дней
        $ip = $request->ip();

        if ($isEnabled) {
            $routeName = $request->route()->getName();

            if ($routeName && !str_starts_with($routeName, 'admin.')) {
                $views = $this->getViewsFromCookie();

                if (!in_array($routeName, $views)) {
                    $views[] = $routeName;
                    $this->storeViewsInCookie($views, $cookieLifetime);

                    if (!$this->isVisitorExists($ip, $request->path())) {
                        $this->createVisitorLog($routeName, $request->path(), $ip);
                    }
                }
            }

            if ($this->isSavePeriod()) {
                $this->createStatisticLog($views);
            }
        }

        return $request;
    }

    protected function getViewsFromCookie()
    {
        return Cookie::get('views') ? json_decode(Cookie::get('views'), true) : [];
    }

    protected function storeViewsInCookie($views, $lifetime)
    {
        Cookie::queue('views', json_encode($views), $lifetime);
    }

    protected function isVisitorExists($ip, $routePath)
    {
        return Visitor::where('ip_address', $ip)
            ->where('route', $routePath)
            ->exists();
    }

    protected function isSavePeriod()
    {
        $savePeriod = config('analytics.save_period', 60 * 24 * 7); // 7 дней
        return Carbon::now()->minute % ($savePeriod * 60) == 0;
    }

    protected function createVisitorLog($routeName, $routePath, $ip)
    {
        Visitor::create([
            'category' => 'route',
            'value' => $routeName,
            'route' => $routePath,
            'ip_address' => $ip,
        ]);

        if (config('analytics.logger')) {
            Log::info("Route {$routeName} visited by {$ip}!");
        }
    }

    protected function createStatisticLog($views)
    {
        try {
            Statistic::create([
                'category' => 'route',
                'data' => json_encode($views),
            ]);

            if (config('analytics.logger')) {
                Log::info("Created logs to models Statistic!");
            }
        } catch (\Exception $e) {
            if (config('analytics.logger')) {
                Log::error("Failed to create logs to models Statistic: {$e->getMessage()}");
            }
        }
    }
}