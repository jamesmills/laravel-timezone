<?php

namespace JamesMills\LaravelTimezone\Test\Helpers\Models;

/**
 *
 * @property string $email
 * @property string $timezone
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class User extends \Illuminate\Foundation\Auth\User
{
    protected $fillable = [
        'email',
        'timezone',
    ];
}