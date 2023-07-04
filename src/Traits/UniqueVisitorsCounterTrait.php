<?php

namespace  Sashagm\Analytics\Traits;

use Exception;
use Closure;
use Sashagm\Analytics\Models\Visitor;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cookie;


trait UniqueVisitorsCounterTrait
{
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
                $user = "user";
            } else {
                // обработка для поисковых роботов
                $user = "bot";
            }

            try {
                $visitorId = Cookie::get('visitor_id');

                if (!$visitorId) {
                    $visitorId = uniqid();
                    Cookie::queue('visitor_id', $visitorId, $cookieLifetime);

                    // Проверяем, существует ли уже запись с таким же IP-адресом и значением пользователя
                    $existingVisitor = Visitor::where('category', $user)
                        ->where('ip_address', $ip)
                        ->first();

                    if (!$existingVisitor) {
                        $this->createVisitorLog($user, $visitorId, $ip);
                    }
                }
            } catch (\Exception $e) {
                if (config('analytics.logger')) {
                    Log::error("Failed to create logs to models Visitor: {$e->getMessage()}");
                }
            }
        }

        return $request;
    }

    protected function createVisitorLog($user, $visitorId, $ip)
    {
        Visitor::create([
            'category' => $user,
            'value' => $visitorId,
            'ip_address' => $ip,
        ]);

        if (config('analytics.logger')) {
            Log::info("New {$user} visited with ID {$visitorId}!");
        }
    }
}
