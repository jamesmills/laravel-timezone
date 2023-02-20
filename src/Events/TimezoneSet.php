<?php

namespace JamesMills\LaravelTimezone\Events;

use Illuminate\Foundation\Events\Dispatchable;

class TimezoneSet
{
    use Dispatchable;

    public function __construct(public string $timezone)
    {
        //
    }
}
