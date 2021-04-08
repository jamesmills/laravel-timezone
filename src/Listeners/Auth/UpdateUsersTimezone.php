<?php

namespace JamesMills\LaravelTimezone\Listeners\Auth;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Auth;
use JamesMills\LaravelTimezone\Traits\RetrievesGeoIpTimezone;
use Laravel\Passport\Events\AccessTokenCreated;

class UpdateUsersTimezone
{
    use RetrievesGeoIpTimezone;

    private function notify(array $info): void
    {
        if (config('timezone.flash') == 'off') {
            return;
        }

        // TODO(sergotail): move to config, separate null and default timezone message case
        $message = 'We have set your timezone to ' . $info['timezone'];

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

    private function getFromLookup(): ?string
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

    private function lookup($type, $keys): ?string
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

    public function handle($event): void
    {
        $user = null;

        /**
         * If the event is AccessTokenCreated,
         * we logged the user and return,
         * stopping the execution.
         *
         * The Auth::loginUsingId dispatches a Login event,
         * making this listener be called again.
         */
        if ($event instanceof AccessTokenCreated) {
            Auth::loginUsingId($event->userId);

            return;
        }

        /**
         * If the event is Login, we get the user from the web guard.
         */
        if ($event instanceof Login) {
            $user = Auth::user();
        }

        /**
         * If no user is found, we just return. Nothing to do here.
         */
        if (is_null($user)) {
            return;
        }

        if ($user->timezone === null || config('timezone.overwrite', false) === true) {
            $info = $this->getGeoIpTimezone($this->getFromLookup());

            if ($user->timezone === null || $user->timezone != $info['timezone']) {
                if ($info['timezone'] !== null) {
                    $user->timezone = $info['timezone'];
                    $user->save();
                }

                $this->notify($info);
            }
        }
    }
}
