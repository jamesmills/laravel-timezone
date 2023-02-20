<?php

namespace JamesMills\LaravelTimezone\Facades;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Carbon\FactoryImmutable;
use Illuminate\Support\Facades\Facade;

/**
 * @method static string getCurrentTimezone()
 * @method static FactoryImmutable getCarbonFactory($user)
 * @method static string|CarbonImmutable convertToLocal(null|Carbon|CarbonImmutable $date, string $format = null)
 * @method static string formatLocal(null|Carbon|CarbonImmutable $date, string $format = null)
 * @method static CarbonImmutable convertFromLocal(mixed $date)
 * @method static CarbonImmutable today()
 * @method static CarbonImmutable now()
 *
 * @see \JamesMills\LaravelTimezone\Timezone
 */
class Timezone extends Facade
{
    public static function getFacadeAccessor()
    {
        return \JamesMills\LaravelTimezone\Timezone::class;
    }
}
