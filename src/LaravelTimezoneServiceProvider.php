<?php

namespace JamesMills\LaravelTimezone;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use JamesMills\LaravelTimezone\Listeners\Auth\UpdateUsersTimezone;

class LaravelTimezoneServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;


    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // Register an event listener
        $this->registerEventListener();

        // Allow config publish
        $this->publishes([
            __DIR__ . '/config/timezone.php' => config_path('timezone.php'),
        ], 'config');

        // Register a blade directive to show user date/time in their timezone
        Blade::directive(
            'displayDate',
            function ($expression) {
                $options = explode(',', $expression);

                if (count($options) == 1) {
                    return "<?php echo e(Timezone::convertToLocal($options[0])); ?>";
                } elseif (count($options) == 2) {
                    return "<?php echo e(Timezone::convertToLocal($options[0], $options[1])); ?>";
                } elseif (count($options) == 3) {
                    return "<?php echo e(Timezone::convertToLocal($options[0], $options[1], $options[2])); ?>";
                } else {
                    return 'error';
                }
            }
        );
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('timezone', Timezone::class);

        $this->mergeConfigFrom(
            __DIR__ . '/config/timezone.php',
            'timezone'
        );
    }

    /**
     *
     */
    private function registerEventListener(): void
    {
        $events = [
            \Illuminate\Auth\Events\Login::class,
            \Laravel\Passport\Events\AccessTokenCreated::class,
        ];

        Event::listen($events, UpdateUsersTimezone::class);
    }
}
