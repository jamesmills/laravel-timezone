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
