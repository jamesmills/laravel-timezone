<?php

namespace JamesMills\LaravelTimezone;

use Carbon\Carbon;

class Timezone
{
    /**
     * @param  Carbon\Carbon|null  $date
     * @param  null  $format
     * @param  bool  $format_timezone
     * @return string
     */
    public function convertToLocal(?Carbon $date, $format = null, $format_timezone = false, $enableTranslation = null) : string
    {
        if (is_null($date)) {
            return __('Empty');
        }

        $timezone = (auth()->user()->timezone) ?? config('app.timezone');
        
        $enableTranslation = $enableTranslation !== null ? $enableTranslation : config('timezone.enableTranslation');
        
        $date->setTimezone($timezone);

        if (is_null($format)) {
            return $enableTranslation ? $date->translatedFormat(config('timezone.format')) : $date->format(config('timezone.format'));
        }

        $formatted_date_time = $enableTranslation ? $date->translatedFormat($format) : $date->format($format);

        if ($format_timezone) {
            return $formatted_date_time . ' ' . $this->formatTimezone($date);
        }

        return $formatted_date_time;
    }

    /**
     * @param $date
     * @return Carbon\Carbon
     */
    public function convertFromLocal($date) : Carbon
    {
        return Carbon::parse($date, auth()->user()->timezone)->setTimezone('UTC');
    }

    /**
     * @param  Carbon\Carbon  $date
     * @return string
     */
    private function formatTimezone(Carbon $date) : string
    {
        $timezone = $date->format('e');
        $parts = explode('/', $timezone);

        if (count($parts) > 1) {
            return str_replace('_', ' ', $parts[1]) . ', ' . $parts[0];
        }

        return str_replace('_', ' ', $parts[0]);
    }
}
