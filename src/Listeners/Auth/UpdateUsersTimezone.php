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

                if (config('timezone.flash') == 'laracasts') {
                    flash()->success('We have set your timezone to ' . $geoip_info['timezone']);
                } else {
                    request()->session()->flash('success', 'We have set your timezone to ' . $geoip_info['timezone']);
                }
            }
        }
    }
}
