<?php

namespace App\Listeners\Auth;

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
            $user = auth()->user();
            $user->timezone = $geoip_info['timezone'];
            $user->save();

            flash()->success('We have set your timezone to ' . $geoip_info['timezone']);
        }
    }
}
