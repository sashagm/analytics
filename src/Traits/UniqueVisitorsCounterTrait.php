<?php

namespace  Sashagm\Analytics\Traits;


use Exception;
use Closure;
use Sashagm\Analytics\Models\Visitor;
use Illuminate\Support\Facades\Cookie;


trait UniqueVisitorsCounterTrait
{

    public function starting($request)
    {

        $ip = $_SERVER['REMOTE_ADDR'];
        $ua = strtolower($_SERVER['HTTP_USER_AGENT']);
        $isBot = preg_match('/bot|crawl|slurp|spider/i', $ua);
        
        if (!$isBot) {
            // обработка для обычных пользователей
            $user = "user";
        } else {
            // обработка для поисковых роботов
            $user = "bot";
        }
        
        
        $visitorId = Cookie::get('visitor_id');

        if (!$visitorId) {
            $visitorId = uniqid();
            Cookie::queue('visitor_id', $visitorId, 60 * 24 * 30);

            Visitor::create([
                'category' => $user,
                'value' => $visitorId,
                'ip_address' => $ip, 
            ]);
        }

        return $request;

    }

}

