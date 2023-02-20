<?php

namespace JamesMills\LaravelTimezone\Listeners\Auth;

use Illuminate\Support\Facades\Auth;
use JamesMills\LaravelTimezone\Events\TimezoneSet;
use Stevebauman\Location\Facades\Location;

class UpdateUsersTimezone
{
    /**
     * Handle the event.
     *
     * @param $event
     * @return void
     */
    public function handle($event): void
    {
        if (empty(config('timezone.events'))) {
            return;
        }

        $user = $event->user ?? Auth::user();

        if (
            (!$user->timezone || config('timezone.overwrite')) &&
            $position = Location::get()
        ) {
            $user->timezone = $position->timezone;
            $user->save();

            event(new TimezoneSet($user->timezone));
        }
    }
}
