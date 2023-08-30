<?php

namespace  Sashagm\Analytics\Traits;

use Illuminate\Support\Facades\Log;


trait BuildsLoggers
{


    public function logger($method, $mess)
    {


        $view =  config('analytics.logger_method');
        
        switch ($view) {
            case true:

                Log::$method($mess);

                break;

            case false:

                $logFilePath = storage_path(config('analytics.logger_path'));
                $logger = Log::build([
                    'driver' => 'single',
                    'path' => $logFilePath,
                    'level' => 'debug',
                ]);
                $logger->$method($mess);
        
                break;

            default:
                
                break;
        }

        
    }



}

