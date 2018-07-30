<?php

namespace JamesMills\LaravelTimezone;

class Timezone
{
    public function convertoToLocal($date, $format = 'jS F Y g:i:a', $format_timezone = false)
    {
        if (is_null($date)) {
            return 'Empty';
        }

        $timezone = (auth()->user()->timezone) ?? config('app.timezone');

        $formatted_date_time = $date->setTimezone($timezone)->format($format);

        if ($format_timezone) {
            $formatted_date_time .= ' ' . $this->formatTimezone($date);
        }

        return $formatted_date_time;
    }

    public function formatTimezone($date)
    {
        $timezone = $date->format('e');
        $parts = explode('/', $timezone);

        if (count($parts) > 1) {
            return str_replace('_', ' ', $parts[1]) . ', ' . $parts[0];
        }

        return str_replace('_', ' ', $parts[0]);
    }

}
