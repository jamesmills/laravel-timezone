<?php

namespace JamesMills\LaravelTimezone;

use Carbon\Carbon;

class Timezone
{
    protected function formatTimezone(Carbon $date): string
    {
        $timezone = $date->format('e');
        $parts = explode('/', $timezone);

        if (count($parts) > 1) {
            return str_replace('_', ' ', $parts[1]) . ', ' . $parts[0];
        }

        return str_replace('_', ' ', $parts[0]);
    }

    public function toLocal(?Carbon $date): ?Carbon
    {
        if ($date === null) {
            return null;
        }

        // TODO(sergotail): use geoip timezone suggestion for non-authorized users too
        // (make it configurable)
        $timezone = auth()->user()->getTimezone() ??
            config('timezone.default', null) ??
            config('app.timezone');

        return $date->copy()->setTimezone($timezone);
    }

    public function convertToLocal(
        ?Carbon $date,
        ?string $format = null,
        bool $displayTimezone = false
    ): string {
        $date = $this->toLocal($date);

        if ($date === null) {
            return config('timezone.empty_date', 'Empty');
        }

        $formatted = $date->format($format ?? config('timezone.format'));

        if ($displayTimezone) {
            return $formatted . ' ' . $this->formatTimezone($date);
        }

        return $formatted;
    }

    public function convertFromLocal($date): Carbon
    {
        return Carbon::parse($date, auth()->user()->getTimezone())->setTimezone('UTC');
    }
}
