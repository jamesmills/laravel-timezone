<?php

namespace JamesMills\LaravelTimezone;

use Carbon\Carbon;

class Timezone
{
    public function convertToLocal(Carbon $date, $format = null, $format_timezone = false)
    {
        if (is_null($date)) {
            return 'Empty';
        }

        $timezone = (auth()->user()->timezone) ?? config('app.timezone');

        $date->setTimezone($timezone);

        if (is_null($format)) {
            return $date->format(config('timezone.format'));
        }

        $formatted_date_time = $date->format($format);

        if ($format_timezone) {
            return $formatted_date_time . ' ' . $this->formatTimezone($date);
        }

        return $formatted_date_time;
    }

    public function convertFromLocal($date) : Carbon
    {
        return Carbon::parse($date, auth()->user()->timezone)->setTimezone('UTC');
    }

    public function formatTimezone(Carbon $date)
    {
        $timezone = $date->format('e');
        $parts = explode('/', $timezone);

        if (count($parts) > 1) {
            return str_replace('_', ' ', $parts[1]) . ', ' . $parts[0];
        }

        return str_replace('_', ' ', $parts[0]);
    }
}
