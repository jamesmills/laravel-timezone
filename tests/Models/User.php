<?php

namespace JamesMills\LaravelTimezone\Tests\Models;

use JamesMills\LaravelTimezone\Traits\HasCarbonFactory;

class User extends \Illuminate\Foundation\Auth\User
{
    use HasCarbonFactory;

    protected $guarded = [];
}
