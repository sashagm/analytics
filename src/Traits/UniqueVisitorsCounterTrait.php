<?php

namespace  Sashagm\Analytics\Traits;

use Exception;
use Closure;
use Sashagm\Analytics\Models\Visitor;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cookie;


trait UniqueVisitorsCounterTrait
{

    use BuildsLoggers;

    public function run($request)
    {
        $isEnabled = config('analytics.enabled', true);
        $cookieLifetime = config('analytics.cookie_lifetime', 60 * 24 * 30); // 30 дней
        $ip = $request->ip();
        $ua = strtolower($request->userAgent());
        $isBot = preg_match('/bot|crawl|slurp|spider/i', $ua);

        if ($isEnabled) {
            if (!$isBot) {
                // обработка для обычных пользователей
                $user = config('analytics.provider.users');
            } else {
                // обработка для поисковых роботов
                $user = config('analytics.provider.bots');
            }

            try {
                $visitorId = Cookie::get('visitor_id');

                if (!$visitorId) {
                    $visitorId = uniqid();
                    Cookie::queue('visitor_id', $visitorId, $cookieLifetime);

                    // Проверяем, существует ли уже запись с таким же IP-адресом и значением пользователя
                    if (!$this->isVisitorExists($user, $ip)) {
                        $this->createVisitorLog($user, $visitorId, $ip);
                    }
                }
            } catch (\Exception $e) {
                if (config('analytics.logger')) {

                    $this->logger('error', "Failed to create logs to models Visitor: {$e->getMessage()}");

                }
            }
        }

        return $request;
    }

    protected function isVisitorExists($user, $ip)
    {
        return Visitor::where('category', $user)
            ->where('ip_address', $ip)
            ->exists();
    }

    protected function createVisitorLog($user, $visitorId, $ip)
    {
        Visitor::create([
            'category' => $user,
            'value' => $visitorId,
            'ip_address' => $ip,
        ]);

        if (config('analytics.logger')) {

            $this->logger('info', "New {$user} visited with ID {$visitorId}!");

        }
    }
}
