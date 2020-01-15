# Laravel Timezone

[![Packagist](https://img.shields.io/packagist/v/jamesmills/laravel-timezone.svg?style=for-the-badge)](https://packagist.org/packages/jamesmills/laravel-timezone)
![Packagist](https://img.shields.io/packagist/dt/jamesmills/laravel-timezone.svg?style=for-the-badge)
[![Packagist](https://img.shields.io/packagist/l/jamesmills/laravel-timezone.svg?style=for-the-badge)]()
[![Buy us a tree](https://img.shields.io/badge/Treeware-%F0%9F%8C%B3-lightgreen?style=for-the-badge)](https://offset.earth/treeware?gift-trees)

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

There isn't much to configure right now. You only need to do this if you want to use Laracast Flash Messages or restrict overwriting the database.

```php
php artisan vendor:publish --provider="JamesMills\LaravelTimezone\LaravelTimezoneServiceProvider" --tag=config
```

If you wish to customise the underlying `torann/geoip` package you can publish the config file by using the command below.

```php
php artisan vendor:publish --provider="Torann\GeoIP\GeoIPServiceProvider" --tag=config
```

### Flash Messages

By default, when the timezone has been set, there is a flash message set in the session. There is an optional integration to use [laracasts/flash](https://github.com/laracasts/flash) package if you wish. Just publish the config options and override the default settings.  


### Overwrite existing timezones in the database

By default, the timezone will be overwritten at each login with the current user timezone. This behavior can be restricted to only update the timezone if it is blank by setting the `'overwrite' => false,` config option.

### Default Format

By default, the date format will be `jS F Y g:i:a`. To override this configuration, you just need to change the `format` property inside the configuration file ´config/timezone.php` for the desired format.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Treeware

You're free to use this package, but if it makes it to your production environment you are required to buy the world a tree.

It’s now common knowledge that one of the best tools to tackle the climate crisis and keep our temperatures from rising above 1.5C is to <a href="https://www.bbc.co.uk/news/science-environment-48870920">plant trees</a>. If you contribute to my forest you’ll be creating employment for local families and restoring wildlife habitats.

You can buy trees at for my forest here [offset.earth/treeware](https://offset.earth/treeware?gift-trees)

Read more about Treeware at [treeware.earth](http://treeware.earth?utm_referrer=github_licence_link)

## Issues

If you receive a message like `This cache store does not support tagging` this is because the `torann/geoip` package requires a caching driver which supports tagging and you probably have your application set to use the `file` cache driver. You can [publish the config file](#custom-configuration) for the `torann/geoip` package and set `'cache_tags' => null,` to solve this. [Read more about this issue here](https://github.com/jamesmills/laravel-timezone/issues/4#issuecomment-494648925).
