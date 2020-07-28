<?php

namespace JamesMills\LaravelTimezone\Test;

use Illuminate\Database\Schema\Blueprint;
use JamesMills\LaravelTimezone\Facades\Timezone as TimezoneFacade;
use JamesMills\LaravelTimezone\Test\Helpers\Models\User;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{

    /** @var User */
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpDatabase();
    }

    /**
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set(
            'database.connections.testbench',
            [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => '',
            ]
        );
    }

    protected function getPackageAliases($app)
    {
        return [
            'Timezone' => TimezoneFacade::class,
        ];
    }
    protected function getApplicationTimezone($app)
    {
        return 'UTC';
    }

    protected function setUpDatabase()
    {
        $this->app['db']->connection()->getSchemaBuilder()->create(
            'users',
            function (Blueprint $table) {
                $table->id();
                $table->string('email');
                $table->string('timezone');
                $table->timestamps();
            }
        );

        $this->user = User::create(
            [
                'email' => 'test@mail.com',
                'timezone' => 'Asia/Manila',
            ]
        );
    }
}