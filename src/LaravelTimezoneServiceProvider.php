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

    private function registerEventListener(): void
    {
        Event::listen(
            config('timezone.timezone_check.events', null) ?? [
                \Illuminate\Auth\Events\Login::class,
                \Laravel\Passport\Events\AccessTokenCreated::class,
            ],
            config('timezone.timezone_check.listener', null) ?? UpdateUsersTimezone::class
        );
    }

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // Allow migrations publish
        if (!class_exists('AddTimezoneColumnsToUsersTable')) {
            $this->publishes([
                __DIR__ . '/database/migrations/add_timezone_columns_to_users_table.php.stub' => database_path('/migrations/' . date('Y_m_d_His') . '_add_timezone_columns_to_users_table.php'),
            ], 'migrations');
        }

        // Register the Timezone alias
        AliasLoader::getInstance()->alias('Timezone', \JamesMills\LaravelTimezone\Facades\Timezone::class);

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
}
