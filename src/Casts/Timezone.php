<?php

namespace JamesMills\LaravelTimezone\Casts;

use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use \JamesMills\LaravelTimezone\Facades\Timezone as TimezoneFacade;

class Timezone implements CastsAttributes
{
    /**
     * @var null
     */
    private $format;

    /**
     * @var bool
     */
    private $showTimezone;

    /**
     * Timezone constructor.
     * @param  null  $format
     * @param  bool  $showTimezone
     */
    public function __construct($format = null, $showTimezone = false)
    {
        $this->format = $format === 'null' ? null : $format;
        $this->showTimezone = filter_var($showTimezone, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return array
     */
    public function get($model, $key, $value, $attributes)
    {
        return TimezoneFacade::convertToLocal(Carbon::parse($value), $this->format, $this->showTimezone);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  array  $value
     * @param  array  $attributes
     * @return string
     */
    public function set($model, $key, $value, $attributes)
    {
        return TimezoneFacade::convertFromLocal($value);
    }
}
