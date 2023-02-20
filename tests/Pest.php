<?php

use JamesMills\LaravelTimezone\Tests\Models\User;
use JamesMills\LaravelTimezone\Tests\TestCase;

uses(TestCase::class)->in(__DIR__);

function seedUser(array $attributes = []): User
{
    return User::create([
        'name' => fake()->name(),
        'email' => fake()->email(),
        'password' => bcrypt('secret'),
        'timezone' => fake()->timezone(),
        'locale' => fake()->locale(),
        ...$attributes,
    ]);
}

function logIn(array $attributes = []): User
{
    test()->user = seedUser($attributes);
    test()->be(test()->user);

    return test()->user;
}
