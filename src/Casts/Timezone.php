<?php

namespace JamesMills\LaravelTimezone\Casts;

use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use JamesMills\LaravelTimezone\Facades\Timezone as TimezoneFacade;
use JamesMills\LaravelTimezone\Traits\TimezoneTrait;

class Timezone implements CastsAttributes
{
    use TimezoneTrait;

    /**
     * Transform the attribute from the underlying model values.
     *
     * @param  Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return Carbon
     * @throws Exception
     */
    public function get($model, string $key, $value, array $attributes)
    {
        return Carbon::parse($value)
            ->setTimezone($this->getUserTimezone());
    }

    /**
     * Transform the attribute to its underlying model values.
     *
     * @param  Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return Carbon
     */
    public function set($model, string $key, $value, array $attributes)
    {
        return TimezoneFacade::convertFromLocal($value);
    }
}
