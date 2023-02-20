<?php

namespace JamesMills\LaravelTimezone\Traits;

use Carbon\CarbonImmutable;
use Carbon\FactoryImmutable;
use JamesMills\LaravelTimezone\Facades\Timezone;

trait HasCarbonFactory
{
    public function getCarbonFactory(): FactoryImmutable
    {
        return Timezone::getCarbonFactory($this);
    }

    public function now(): CarbonImmutable
    {
        return $this->getCarbonFactory()
            ->now();
    }

    public function today(): CarbonImmutable
    {
        return $this->getCarbonFactory()
            ->today();
    }
}