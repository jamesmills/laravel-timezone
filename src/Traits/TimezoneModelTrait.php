<?php

namespace JamesMills\LaravelTimezone\Traits;

trait TimezoneModelTrait
{
    /**
     * @return mixed
     */
    public function getTimezone()
    {
        return $this->timezone;
    }

    /**
     * @param  string  $timezone
     * @return $this
     */
    public function setTimezone(string $timezone)
    {
        $this->timezone = $timezone;

        return $this;
    }
}
