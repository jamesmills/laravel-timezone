<?php

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use JamesMills\LaravelTimezone\Facades\Timezone;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

it('can detect user timezone', function (string $timezone) {
    expect(timezone())->toEqual($timezone);
})->with([
    'a user that has a timezone' => fn () => logIn()->timezone,
    'a user without a timezone' => function () {
        logIn(['timezone' => null]);

        return 'UTC';
    },
]);

it('can convert date to local time object', function () {
    $user = logIn();
    $date = CarbonImmutable::create(2000, 4, 1, 15);
    $expected = $date->setTimezone($user->timezone);

    expect($expected)->toEqual(Timezone::convertToLocal($date))
        ->and($expected)->toEqual(to_local_timezone($date));
});

it('can convert date to local time formatted', function (?string $format) {
    $user = logIn();
    $date = CarbonImmutable::create(2000, 4, 1, 15);
    $expected = $date->setTimezone($user->timezone);
    $format = $format ?? config('timezone.format');

    expect($expected->format($format))->toEqual(Timezone::formatLocal($date, $format));
})->with([
    'using default format' => null,
    'just days' => 'Y-m-d',
    'day and time' => 'Y-m-d g:ia',
]);

it('can convert from local timezone', function () {
    $user = logIn(['timezone' => 'Asia/Shanghai']);
    $userDate = Carbon::now($user->timezone);
    $converted = from_local_timezone($userDate);
    ray($userDate, $converted);

    expect($userDate->toDateTimeString())
        ->not()->toEqual($converted->toDateTimeString());
});

it('can make local today', function () {
    $user = logIn();
    $date = today($user->timezone);

    expect($date)->toEqual(local_today());
});

it('can make local now', function () {
    $user = logIn();
    $date = now($user->timezone)
        ->startOfMinute();

    expect($date)->toEqual(local_now()->startOfMinute());
});
