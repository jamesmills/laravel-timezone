<?php

namespace JamesMills\LaravelTimezone;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Carbon\FactoryImmutable;
use Illuminate\Support\Facades\Auth;

class Timezone
{
    /**
     * Gets the current user's timezone and if not present,
     * the app's timezone as a fallback.
     *
     * @return string
     */
    public function getCurrentTimezone(): string
    {
        return Auth::user()?->timezone ?? config('app.timezone');
    }

    public function getCarbonFactory(object $user = null): FactoryImmutable
    {
        $factory = (new FactoryImmutable([
                'locale' => config('app.locale'),
                'timezone' => config('app.timezone'),
            ]))
            ->setClassName(CarbonImmutable::class);

        if ($user = $user ?? Auth::user()) {
            $factory->mergeSettings([
                'locale' => $user->locale ?? config('app.locale'),
                'timezone' => $user->timezone ?? config('app.timezone'),
            ]);
        }

        return $factory;
    }

    /**
     * Converts a date to the current user's timezone.
     * Optionally pass a format to format the date, otherwise
     * returns the updated CarbonImmutable instance.
     *
     * @param Carbon|CarbonImmutable|null $date
     * @param string|null $format
     * @return string|CarbonImmutable
     */
    public function convertToLocal(null|Carbon|CarbonImmutable $date, string $format = null): string|CarbonImmutable
    {
        $date = $date ?? now();

        $converted = $this->getCarbonFactory()
            ->make($date);

        if (!$format) {
            return $converted;
        }

        return $converted->format($format);
    }

    public function formatLocal(null|Carbon|CarbonImmutable $date, string $format = null): string
    {
        $date = $date ?? now();

        return $this->convertToLocal($date, $format ?? config('timezone.format'));
    }

    /**
     * Parses a date from the local user's timezone
     * and converts it to the app's timezone for storage.
     *
     * @param mixed $date
     * @return CarbonImmutable
     */
    public function convertFromLocal(mixed $date): CarbonImmutable
    {
        return $this->getCarbonFactory()
            ->parse($date, $this->getCurrentTimezone())
            ->setTimezone(config('app.timezone'));
    }

    /**
     * Gets the user's 'today' date object.
     *
     * @return CarbonImmutable
     */
    public function today(): CarbonImmutable
    {
        return $this->getCarbonFactory()
            ->today();
    }

    /**
     * Gets the user's 'now' date object.
     *
     * @return CarbonImmutable
     */
    public function now(): CarbonImmutable
    {
        return $this->getCarbonFactory()
            ->now();
    }
}
