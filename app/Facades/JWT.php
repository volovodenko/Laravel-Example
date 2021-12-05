<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;
use App\Services\JWT as JWTService;

class JWT extends Facade
{
    protected static function getFacadeAccessor()
    {
        return JWTService::class;
    }
}
