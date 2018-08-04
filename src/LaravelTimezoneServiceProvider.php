<?php

namespace JamesMills\LaravelTimezone;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
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
        // Register database migrations
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        // Register the Timezone alias
        AliasLoader::getInstance()->alias('Timezone', \JamesMills\LaravelTimezone\Facades\Timezone::class);

        // Register an event listener
        Event::listen(\Illuminate\Auth\Events\Login::class, UpdateUsersTimezone::class);

        // Allow config publish
        $this->publishes([
            __DIR__ . '/config/timezone.php' => config_path('timezone.php'),
        ], 'config');

        // Register a blade directive to show user date/time in their timezone
        Blade::directive(
            'displayDate',
            function ($expression) {

                // list($date, $format, $format_timezone) = explode(',', str_replace(' ', '', $expression));
                $options = explode(',', str_replace(' ', '', $expression));

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
    }
}
