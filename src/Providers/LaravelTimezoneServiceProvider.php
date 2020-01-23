<?php

namespace JamesMills\LaravelTimezone\Providers;

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
        // Allow migrations publish
        if (! class_exists('AddTimezoneColumnToUsersTable')) {
            $this->publishes($this->migrationHandler(), 'migrations');
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
                }

                if (count($options) == 2) {
                    return "<?php echo e(Timezone::convertToLocal($options[0], $options[1])); ?>";
                }

                if (count($options) == 3) {
                    return "<?php echo e(Timezone::convertToLocal($options[0], $options[1], $options[2])); ?>";
                }

                return 'error';
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

    /**
     * @return array
     */
    private function migrationHandler(): array
    {
        $from = __DIR__ . '/database/migrations/add_timezone_column_to_users_table.php.stub';
        $to = database_path('/migrations/' . date('Y_m_d_His') . '_add_timezone_column_to_users_table.php');

        return [
            $from => $to,
        ];
    }
}
