<?php


return [

    'enabled'           => env('UNIQUE_ENABLED', true),
    'cookie_lifetime'   => env('UNIQUE_VIEWS_TIME', 60),
    'save_period'       => env('UNIQUE_VISITORS_TIME', 1440),
    'logger'            => env('UNIQUE_LOGS', true),


];





