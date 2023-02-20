<?php

namespace JamesMills\LaravelTimezone\Tests;

use JamesMills\LaravelTimezone\Tests\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use JamesMills\LaravelTimezone\LaravelTimezoneServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Stevebauman\Location\LocationServiceProvider;

class TestCase extends Orchestra
{
    public User $user;

    protected function getPackageProviders($app): array
    {
        return [
            LaravelTimezoneServiceProvider::class,
            LocationServiceProvider::class,
        ];
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadLaravelMigrations();

        $migration = include __DIR__.'/../src/database/migrations/add_timezone_column_to_users_table.php.stub';
        $migration->up();
    }

    public function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');
    }

    public function defineRoutes($router)
    {
        // This mocks a login to trigger the event
        $router->post('/login', function (Request $request) {
            $user = User::findOrFail($request->input('id'));
            Auth::login($user);

            return response()->json();
        });
    }
}
