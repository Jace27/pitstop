<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class Logger
{
    public static function log($data): void
    {
        $data = print_r($data, true);
        Log::debug($data);
    }

    public static function error($data): void
    {
        $data = print_r($data, true);
        Log::error($data);
    }
}
