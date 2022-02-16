# Laravel Timezone

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jamesmills/laravel-timezone.svg?style=flat-square)](https://packagist.org/packages/jamesmills/laravel-timezone)
[![Total Downloads](https://img.shields.io/packagist/dt/jamesmills/laravel-timezone.svg?style=flat-square)](https://packagist.org/packages/jamesmills/laravel-timezone)
[![Licence](https://img.shields.io/packagist/l/jamesmills/laravel-timezone.svg?style=flat-square)](https://packagist.org/packages/jamesmills/laravel-timezone)
[![Quality Score](https://img.shields.io/scrutinizer/g/jamesmills/laravel-timezone.svg?style=flat-square)](https://scrutinizer-ci.com/g/jamesmills/laravel-timezone)
[![StyleCI](https://github.styleci.io/repos/142882574/shield?branch=master)](https://github.styleci.io/repos/142882574)
[![Buy us a tree](https://img.shields.io/badge/treeware-%F0%9F%8C%B3-lightgreen?style=flat-square)](https://plant.treeware.earth/jamesmills/laravel-timezone)
[![Treeware (Trees)](https://img.shields.io/treeware/trees/jamesmills/laravel-timezone?style=flat-square)](https://plant.treeware.earth/jamesmills/laravel-timezone)

An easy way to set a timezone for a user in your application and then show date/times to them in their local timezone.

## How does it work

This package listens for the `\Illuminate\Auth\Events\Login` event and will then automatically set a `timezone` on your `user` model (stored in the database).

This package uses the [torann/geoip](http://lyften.com/projects/laravel-geoip/doc/) package which looks up the users location based on their IP address. The package also returns information like the users currency and users timezone. [You can configure this package separately if you require](#custom-configuration).

 ## How to use

You can show dates to your user in their timezone by using

```php
{{ Timezone::convertToLocal($post->created_at) }}
```

Or use our nice blade directive

```php
@displayDate($post->created_at)
```

[More examples below](#examples)

## Installation

Pull in the package using Composer

```
composer require jamesmills/laravel-timezone
```

Publish database migrations
 
```
php artisan vendor:publish --provider="JamesMills\LaravelTimezone\LaravelTimezoneServiceProvider" --tag=migrations
```

Run the database migrations. This will add a `timezone` column to your `users` table.

```
php artisan migrate
```

## Examples

### Showing date/time to the user in their timezone

Default will use the format `jS F Y g:i:a` and will not show the timezone

```php
{{ Timezone::convertToLocal($post->created_at) }}

// 4th July 2018 3:32:am
```

If you wish you can set a custom format and also include a nice version of the timezone

```php
{{ Timezone::convertToLocal($post->created_at, 'Y-m-d g:i', true) }}

// 2018-07-04 3:32 New York, America
```

### Using blade directive

Making your life easier one small step at a time

```php
@displayDate($post->created_at)

// 4th July 2018 3:32:am
```

And with custom formatting

```php
@displayDate($post->created_at, 'Y-m-d g:i', true)

// 2018-07-04 3:32 New York, America
```

### Saving the users input to the database in UTC

This will take a date/time, set it to the users timezone then return it as UTC in a Carbon instance.

```php
$post = Post::create([
    'publish_at' => Timezone::convertFromLocal($request->get('publish_at')),
    'description' => $request->input('description'),
]);
```

## Custom Configuration

Publishing the config file is optional.

```php
php artisan vendor:publish --provider="JamesMills\LaravelTimezone\LaravelTimezoneServiceProvider" --tag=config
```

### Flash Messages

When the timezone has been set, we display a flash message, By default, is configured to use Laravel default flash messaging, here are some of the optional integrations.

[laracasts/flash](https://github.com/laracasts/flash) - `'flash' => 'laracasts'`

[mercuryseries/flashy](https://github.com/mercuryseries/flashy) - `'flash' => 'mercuryseries'`

[spatie/laravel-flash](https://github.com/spatie/laravel-flash) - `'flash' => 'spatie'`

[mckenziearts/laravel-notify](https://github.com/mckenziearts/laravel-notify) - `'flash' => 'mckenziearts'`

[usernotnull/tall-toasts](https://github.com/usernotnull/tall-toasts) - `'flash' => 'tall-toasts'`

To override this configuration, you just need to change the `flash` property inside the configuration file `config/timezone.php` for the desired package. You can disable flash messages by setting `'flash' => 'off'`.

### Overwrite existing timezones in the database

By default, the timezone will be overwritten at each login with the current user timezone. This behavior can be restricted to only update the timezone if it is blank by setting the `'overwrite' => false,` config option.

### Default Format

By default, the date format will be `jS F Y g:i:a`. To override this configuration, you just need to change the `format` property inside the configuration file `config/timezone.php` for the desired format.

### Lookup Array

This lookup array configuration makes it possible to find the remote address of the user in any attribute inside the Laravel `request` helper, by any key. Having in mind when the key is found inside the attribute, that key will be used. By default, we use the `server` attribute with the key `REMOTE_ADDR`. To override this configuration, you just need to change the `lookup` property inside the configuration file `config/timezone.php` for the desired lookup.

### User Message

You may configure the message shown to the user when the timezone is set by changing the `message` property inside the configuration file `config/timezone.php`

### Underlying GeoIp Package

If you wish to customise the underlying `torann/geoip` package you can publish the config file by using the command below.

```php
php artisan vendor:publish --provider="Torann\GeoIP\GeoIPServiceProvider" --tag=config
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License

This package is 100% free and open-source, under the MIT license. Use it however you want.

This package is [Treeware](https://treeware.earth). If you use it in production, then we ask that you [**buy the world a tree**](https://plant.treeware.earth/jamesmills/laravel-timezone) to thank us for our work. By contributing to the Treeware forest you’ll be creating employment for local families and restoring wildlife habitats.

## Issues

If you receive a message like `This cache store does not support tagging` this is because the `torann/geoip` package requires a caching driver which supports tagging and you probably have your application set to use the `file` cache driver. You can [publish the config file](#custom-configuration) for the `torann/geoip` package and set `'cache_tags' => null,` to solve this. [Read more about this issue here](https://github.com/jamesmills/laravel-timezone/issues/4#issuecomment-494648925).
