<?php

namespace JamesMills\LaravelTimezone\Listeners\Auth;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

use Laravel\Passport\Events\AccessTokenCreated;
use JamesMills\LaravelTimezone\Traits\FlashesMessage;
use JamesMills\LaravelTimezone\Traits\RetrievesGeoIpTimezone;

class UpdateUsersTimezone
{
    use RetrievesGeoIpTimezone;
    use FlashesMessage;

    private function getFromLookup(): ?string
    {
        $result = null;

        foreach (config('timezone.lookup') as $type => $keys) {
            if (empty($keys)) {
                continue;
            }

            $result = $this->lookup($type, $keys);

            if ($result === null) {
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

    protected function notify(array $info): void
    {
        if (config('timezone.flash') === 'off') {
            return;
        }

        if ($info['timezone'] === null) {
            $key = config('timezone.messages.fail.key', 'error');
            $message = config('timezone.messages.fail.message');
        } else {
            if ($info['default']) {
                $key = config('timezone.messages.default.key', 'warning');
                $message = config('timezone.messages.default.message');
            } else {
                $key = config('timezone.messages.success.key', 'info');
                $message = config('timezone.messages.success.message');
            }

            if ($message !== null) {
                $message = sprintf($message, $info['timezone']);
            }
        }

        if ($message === null) {
            return;
        }

        if (config('timezone.flash') === 'laravel') {
            $this->flashLaravelMessage($key, $message);
            return;
        }

        if (config('timezone.flash') === 'laracasts') {
            $this->flashLaracastsMessage($key, $message);
            return;
        }

        if (config('timezone.flash') === 'mercuryseries') {
            $this->flashMercuryseriesMessage($key, $message);
            return;
        }

        if (config('timezone.flash') === 'spatie') {
            $this->flashSpatieMessage($key, $message);
            return;
        }

        if (config('timezone.flash') === 'mckenziearts') {
            $this->flashMckenzieartsMessage($key, $message);
            return;
        }
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
         * If no user is found or it has no timezone attribute, we just return.
         */
        if ($user === null || !Schema::hasColumn($user->getTable(), 'timezone')) {
            return;
        }

        $overwrite = $user->detect_timezone ?? config('timezone.overwrite', false);

        if ($user->timezone === null || $overwrite === true) {
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
