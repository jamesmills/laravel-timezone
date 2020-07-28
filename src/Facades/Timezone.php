<?php

namespace JamesMills\LaravelTimezone\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Timezone
 *
 * @mixin \JamesMills\LaravelTimezone\Timezone
 */
class Timezone extends Facade
{
    public static function getFacadeAccessor()
    {
        return \JamesMills\LaravelTimezone\Timezone::class;
    }
}
