<?php


return [

    'enabled'           => env('UNIQUE_ENABLED', true),
    'cookie_lifetime'   => env('UNIQUE_VIEWS_TIME', 60),
    'save_period'       => env('UNIQUE_VISITORS_TIME', 1440),
    'logger'            => env('UNIQUE_LOGS', true),
    'logger_method'     => env('UNIQUE_LOGS_DEFAULT_METHOD', false),
    'logger_path'       => env('UNIQUE_LOGS_PATH', 'logs/custom.log'),
    'admin'             => env('UNIQUE_ADMIN', 'admin.'),

    'provider'          => [

        'users'          => env('UNIQUE_PROVIDER_USER', 'User'),
        'bots'           => env('UNIQUE_PROVIDER_BOTS', 'Bots'),
    ],



];
