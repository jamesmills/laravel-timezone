<?php

namespace JamesMills\LaravelTimezone\Traits;

trait RetrievesGeoIpTimezone
{
    protected function getGeoIpTimezone(?string $ip): array
    {
        $info = geoip()->getLocation($ip);

        return [
            'timezone' => ($info['time_zone'] ?? [])['name'] ?? ($info['timezone'] ?? null),
            'default' => $info['default'] ?? false,
        ];
    }
}
