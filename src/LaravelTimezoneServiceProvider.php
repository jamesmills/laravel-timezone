<?php

namespace JamesMills\LaravelTimezone;

use App\Listeners\Auth\UpdateUsersTimezone;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class LaravelTimezoneServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \Illuminate\Auth\Events\Login::class => [
            UpdateUsersTimezone::class,
        ],
    ];

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        AliasLoader::getInstance()->alias('Timezone', \JamesMills\LaravelTimezone\Facades\Timezone::class);

        Blade::directive(
            'displayDate',
            function ($expression) {
                list($date, $format) = explode(',', $expression);
                return  "<?php echo Timezones::convertoToLocal($date, $format); ?>";
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
        $this->commands('\\JamesMills\\LaravelTimezone\\Commands\\LaravelTimezoneCommand');

        $this->app->bind('timezone', Timezone::class);
    }
}
