<?php

namespace JamesMills\LaravelTimezone\Traits;

trait TimezoneTrait
{
    /**
     * @return string
     */
    protected function getUserTimezone(): string
    {
        return (auth()->user()->timezone) ?? config('app.timezone');
    }
}
