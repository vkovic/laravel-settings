# Laravel Settings

[![Build](https://api.travis-ci.org/vkovic/laravel-settings.svg?branch=master)](https://travis-ci.org/vkovic/laravel-settings)
[![Downloads](https://poser.pugx.org/vkovic/laravel-settings/downloads)](https://packagist.org/packages/vkovic/laravel-settings)
[![Stable](https://poser.pugx.org/vkovic/laravel-settings/v/stable)](https://packagist.org/packages/vkovic/laravel-settings)
[![License](https://poser.pugx.org/vkovic/laravel-settings/license)](https://packagist.org/packages/vkovic/laravel-settings)

### Persist application settings in database easily

If you want to save application specific settings and you don't want to create another table/model/logic,
this package is for you.

---

## Compatibility

The package is compatible with Laravel versions `>= 5.5`

## Installation

Install the package via composer:

```bash
composer require vkovic/laravel-settings
```

Run migrations to create table which will be used to store our settings:

```bash
php artisan migrate
```

## Usage

Let's create and retrieve some settings:

```php
// Set setting value as string
Settings::set('foo', 'bar');

// Get setting value
Settings::get('foo'); // : 'bar'

// In case there is no settings found for given key,
// we can pass default value to return
Settings::get('baz', 'default'); // : 'default'
```

Multiple records could be retrieved using `query` method and wildcard `*`:

```php
Settings::set('computer.display.resolution', '1280x1024');
Settings::set('computer.display.brightness', 97);
Settings::set('computer.sound.volume', 54);
Settings::set('computer.mic.volume', 0);

Settings::query('computer.display.*');
// Result:
// [
//     'computer.display.resolution' => '1280x1024',
//     'computer.display.brightness' => 97
// ]

Settings::query('*.sound.*');
// Result:
// [
//     'computer.sound.volume' => 54
// ]

Settings::query('computer.*.volume');
// Result:
// [
//     'computer.sound.volume' => 54,
//     'computer.mic.volume' => 0
// ]

// In case there is no settings found for given query,
// we can pass default value to return
Settings::query('computer.sound.bass', 85); // : 85
```

Beside string, settings can also be stored as integer, float, null, boolean or array:

```php
Settings::set('age', 35);
Settings::set('temperature', 24.7);
Settings::set('value', null);
Settings::set('employed', true);
Settings::set('fruits', ['orange', 'apple']);

Settings::get('age'); // : 35
Settings::get('temperature'); // : 24.7
Settings::get('value', null); // : null
Settings::get('employed'); // : true
Settings::get('fruits'); // : ['orange', 'apple']
```

We can easily check if settings exists without actually retrieving it from our table:

```php
Settings::set('foo', 'bar');

Settings::exists('foo'); // : true
```

Counting all settings records is also a breeze:

```php
Settings::set('a', 'one');
Settings::set('b', 'two');

Settings::count(); // : 2
```

If we need all settings, or just keys, no problem:

```php
Settings::set('a', 'one');
Settings::set('b', 'two');
Settings::set('c', 'three');

// Get all settings
Settings::all(); // : ['a' => 'one', 'b' => 'two', 'c' => 'three']

// Get only keys
Settings::keys(); // : [0 => 'a', 1 => 'b', 2 => 'c']
```

Also, we can remove settings easily:

```php
Settings::set('a', 'one');
Settings::set('b', 'two');
Settings::set('c', 'three');

// Remove settings by key
Settings::remove('a');

// Or array of keys
Settings::remove(['b', 'c']);
```

If, for some reason, we want to delete all settings at once, no problem:

```php
// This will delete all settings!
Settings::purge();
```

---

## Contributing

If you plan to modify this Laravel package you should run tests that comes with it.
Easiest way to accomplish this would be with `Docker`, `docker-compose` and `phpunit`.

First, we need to initialize Docker containers:

```bash
docker-compose up -d
```

After that, we can run tests and watch the output:

```bash
docker-compose exec app vendor/bin/phpunit
```