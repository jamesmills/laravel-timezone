<?php

namespace JamesMills\LaravelTimezone;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use JamesMills\LaravelTimezone\Listeners\Auth\UpdateUsersTimezone;
use Symfony\Component\Finder\SplFileInfo;

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
        $this->publishes($this->getMigrations(), 'migrations');

        // Register the Timezone alias
        AliasLoader::getInstance()->alias('Timezone', \JamesMills\LaravelTimezone\Facades\Timezone::class);

        // Register an event listener
        Event::listen(\Illuminate\Auth\Events\Login::class, UpdateUsersTimezone::class);

        // Allow config publish
        $this->publishes([
            __DIR__.'/config/timezone.php' => config_path('timezone.php'),
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

    private function getMigrations(): array
    {
        $packageMigrationDir = __DIR__.DIRECTORY_SEPARATOR.'database'.DIRECTORY_SEPARATOR.'migrations';
        $applicationMigrationFolder = database_path('migrations').DIRECTORY_SEPARATOR;
        $baseTimestamp = date('Y_m_d_Hi');
        $migrations = [];

        /** @var SplFileInfo $splFileInfo */
        foreach (File::files($packageMigrationDir) as $key => $splFileInfo) {
            $pattern = '/(\d{4}_\d{2}_\d{2}_\d{6}_){1}(.*)/';
            $subject = $splFileInfo->getFilename();
            preg_match($pattern, $subject, $matches);
            $filename = $matches[2];

            $fileTimestamp = $baseTimestamp . str_pad($key, 2, '0', STR_PAD_LEFT);

            $migrations[$splFileInfo->getPathname()] = $applicationMigrationFolder.$fileTimestamp."_".$filename;
        }

        return $migrations;
    }
}
