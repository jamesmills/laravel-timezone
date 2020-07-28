<?php

namespace JamesMills\LaravelTimezone\Listeners\Auth;

use Illuminate\Auth\Events\Login;
use Torann\GeoIP\Location;

class UpdateUsersTimezone
{

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle($event)
    {
        $user = null;

        /**
         * If the event is Login, we get the user from the web guard.
         */
        if ($event instanceof Login) {
            $user = app('auth')->user();
        }

        /**
         * If no user is found, we just return. Nothing to do here.
         */
        if (is_null($user)) {
            return;
        }

        $ip = $this->getFromLookup();
        $geoip_info = geoip()->getLocation($ip);

        if ($user->timezone != $geoip_info['timezone']) {
            if (config('timezone.overwrite') == true || $user->timezone == null) {
                $user->timezone = $geoip_info['timezone'];
                $user->save();

                $this->notify($geoip_info);
            }
        }
    }

    /**
     * @param  Location  $geoip_info
     */
    private function notify(Location $geoip_info)
    {
        if (config('timezone.flash') == 'off') {
            return;
        }

        $message = 'We have set your timezone to '.$geoip_info['timezone'];

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

    /**
     * @return mixed
     */
    private function getFromLookup()
    {
        $result = null;

        foreach (config('timezone.lookup') as $type => $keys) {
            if (empty($keys)) {
                continue;
            }

            $result = $this->lookup($type, $keys);

            if (is_null($result)) {
                continue;
            }
        }

        return $result;
    }

    /**
     * @param $type
     * @param $keys
     *
     * @return string|null
     */
    private function lookup($type, $keys)
    {
        $value = null;

        foreach ($keys as $key) {
            if (!request()->$type->has($key)) {
                continue;
            }
            $value = request()->$type->get($key);
        }

        return $value;
    }
}
