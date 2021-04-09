<?php

namespace JamesMills\LaravelTimezone\Traits;

trait HasTimezone
{
    public function getTimezone(): ?string
    {
        $timezone = $this->timezone;
        if ($timezone === null) {
            return null;
        }

        return (string) $timezone;
    }

    public function getDetectTimezone(): ?bool
    {
        $detectTimezone = $this->detect_timezone;
        if ($detectTimezone === null) {
            return null;
        }

        return (bool) $detectTimezone;
    }

    public function setTimezone(?string $timezone)
    {
        $this->timezone = $timezone;

        return $this;
    }

    public function setDetectTimezone(?bool $detectTimezone)
    {
        $this->detect_timezone = $detectTimezone;

        return $this;
    }
}
