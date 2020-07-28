<?php

namespace JamesMills\LaravelTimezone\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @mixin \JamesMills\LaravelTimezone\Timezone
 */
class TimezoneFacade extends Facade
{
    public static function getFacadeAccessor()
    {
        return \JamesMills\LaravelTimezone\Timezone::class;
    }
}
