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
        $ip = $this->getFromLookup();
        $geoip_info = geoip()->getLocation($ip);

        $user = auth()->user();
        if ($user->timezone != $geoip_info['timezone']) {
            if (config('timezone.overwrite') == true || $user->timezone == null) {
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

    /**
    * @return mixed
    */
    public function getFromLookup()
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
     * @return string|null
     */
    public function lookup($type, $keys)
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
