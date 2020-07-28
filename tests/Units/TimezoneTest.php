<?php

namespace JamesMills\LaravelTimezone\Test\Units;

use Illuminate\Support\Carbon;
use JamesMills\LaravelTimezone\Facades\TimezoneFacade;
use JamesMills\LaravelTimezone\Test\TestCase;

class TimezoneTest extends TestCase
{
    /** @test */
    public function timezone_facades()
    {
        $this->actingAs($this->user);

        $time = Carbon::createFromFormat(
            'm-d-Y H:i',
            '01-01-2020 00:00',
            'UTC'
        );

        $converted = \Timezone::convertToLocal($time, 'm-d-Y H:i');

        $this->assertEquals('12-31-2019 19:00', $converted);
    }

    /** @test */
    public function with_timezone()
    {
        $this->actingAs($this->user);

        $time = Carbon::createFromFormat(
            'm-d-Y H:i',
            '01-01-2020 00:00',
            'UTC'
        );

        $converted = TimezoneFacade::convertToLocal($time, 'm-d-Y H:i', true);

        $this->assertEquals('12-31-2019 19:00 New York, America', $converted);
    }

    /** @test */
    public function format_by_default_config()
    {
        $this->actingAs($this->user);

        $time = Carbon::createFromFormat(
            'm-d-Y H:i',
            '01-01-2020 00:00',
            'UTC'
        );

        $converted = TimezoneFacade::convertToLocal($time, null);

        // test UTC to Asia/Manila UTC +8
        $this->assertEquals('31st December 2019 7:00:pm', $converted);
    }
}