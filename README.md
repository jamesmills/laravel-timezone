# Laravel Timezone

[![Packagist](https://img.shields.io/packagist/v/jamesmills/laravel-timezone.svg?style=flat-square)](https://packagist.org/packages/jamesmills/laravel-timezone)
[![Packagist](https://img.shields.io/packagist/l/jamesmills/laravel-timezone.svg?style=flat-square)]()

This package listens for the `\Illuminate\Auth\Events\Login` event and will then automatically set a `timezone` on your `user` model (stored in the database). It offers a simple way to then show dates to your user in their timezone `{{ Timezone::convertToLocal($post->created_at) }}`.   

***Assumption** You have kept your application default timezone to UTC and you are storing all your dates as UTC in your database, which you should be.*

## Installation

Pull in the package using Composer

```
composer require jamesmills/laravel-timezone
```

Publish and run the database migrations. This will add a `timezone` column to your `users` table (if one does not already exist).

```
php artisan vendor:publish --provider="JamesMills\LaravelTimezone\LaravelTimezoneServiceProvider" --tag="migrations"
php artisan migrate
```

## Flash Messages

By default when the timezone has been set there is a flash message set in the session. There is an optional integration to use [laracasts/flash](https://github.com/laracasts/flash) package if you wish. Just publish the config options (info below) and override the default settings.  


## Custom Configuration

There isn't much to configure right now. You only need to do this if you want to use Laracast Flash Messages
```
php artisan vendor:publish --provider="JamesMills\LaravelTimezone\LaravelTimezoneServiceProvider" --tag="config"
```

## Examples

### Showing date/time to the user in their timezone

Default will use the format `jS F Y g:i:a` and will not show the timezone
```
{{ Timezone::convertToLocal($post->created_at) }}

// 4th July 2018 3:32:am Updated 4th July 2018 5:27:am
```

If you wish you can set a custom format and also include a nice version of the timezone

```
{{ Timezone::convertToLocal($post->created_at, 'Y-m-d', true) }}

// 2018-07-04 New York, America
```

### Saving the users input to the database in UTC

This will take a date/time, set it to the users timezone then return it as UTC in a Carbon instance.

```
$post = Post::create([
    'publish_at' => Timezone::convertFromLocal($request->get('publish_at')),
    'description' => $request->input('description'),
]);
```


