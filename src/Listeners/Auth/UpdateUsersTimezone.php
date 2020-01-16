<?php

namespace JamesMills\LaravelTimezone\Listeners\Auth;

use Illuminate\Auth\Events\Login;

class UpdateUsersTimezone
{

    /**
     * Handle the event.
     *
     * @param  Login $event
     * @return void
     */
    public function handle(Login $event)
    {
        $geoip_info = geoip()->getLocation(request()->server->get('REMOTE_ADDR', null));

        if (auth()->user()->timezone != $geoip_info['timezone']) {
            if (config('timezone.overwrite') == true || auth()->user()->timezone == null) {
                $user = auth()->user();
                $user->timezone = $geoip_info['timezone'];
                $user->save();

                $this->notify($geoip_info);
            }
        }
    }

    /**
     * @param  \Torann\GeoIP\Location  $geoip_info
     */
    public function notify(\Torann\GeoIP\Location $geoip_info)
    {
        if (config('timezone.flash') == 'off') {
            return;
        }

        $message = 'We have set your timezone to ' . $geoip_info['timezone'];

        if (config('timezone.flash') == 'laravel') {
            request()->session()->flash('success', $message);

            return;
        }

        if (config('timezone.flash') == 'laracasts') {
            flash()->success($message);

            return;
        }

        if (config('timezone.flash') == 'mercuryseries') {
            flashy()->success($message);

            return;
        }

        if (config('timezone.flash') == 'spatie') {
            flash()->success($message);

            return;
        }

        if (config('timezone.flash') == 'mckenziearts') {
            notify()->success($message);

            return;
        }
    }
}
