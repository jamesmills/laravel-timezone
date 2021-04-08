<?php

namespace JamesMills\LaravelTimezone\Traits;

trait RetrievesGeoIpTimezone
{
    protected function getGeoIpTimezone(?string $ip): array
    {
        $info = geoip()->getLocation($ip);

        return [
            'timezone' => $info['timezone'] ?? ($info['time_zone'] ?? [])['name'] ?? null,
            'default' => $info['default'] ?? false,
        ];
    }
}
