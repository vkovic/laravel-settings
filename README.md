# Laravel Settings

[![Build](https://api.travis-ci.org/vkovic/laravel-settings.svg?branch=master)](https://travis-ci.org/vkovic/laravel-settings)
[![Downloads](https://poser.pugx.org/vkovic/laravel-settings/downloads)](https://packagist.org/packages/vkovic/laravel-settings)
[![Stable](https://poser.pugx.org/vkovic/laravel-settings/v/stable)](https://packagist.org/packages/vkovic/laravel-settings)
[![License](https://poser.pugx.org/vkovic/laravel-settings/license)](https://packagist.org/packages/vkovic/laravel-settings)

### Persistent application settings storage

If you want to save application specific settings and you don't want to create another table/model/logic,
this package is just for you. It utilizes underlying [vkovic/laravel-meta](https://github.com/vkovic/laravel-meta)
package and it's logic to store settings data in the `meta` table.

> The package is one of three metadata packages based on the same approach:
> - vkovic/laravel-settings (this package - app specific settings meta storage)
> - [vkovic/laravel-meta](https://github.com/vkovic/laravel-meta) (general purpose meta storage)
> - [vkovic/laravel-model-meta](https://github.com/vkovic/laravel-model-meta) (Laravel model related meta storage)
>
> Packages can be used separately or together. Internally they are using same table and share common logic.

---

## Compatibility

The package is compatible with Laravel versions `>= 5.5`

## Installation

Install the package via composer:

```bash
composer require vkovic/laravel-settings
```

The package needs to be registered in service providers, so just add it to providers array:

```php
// File: config/app.php

// ...

'providers' => [

    /*
     * Package Service Providers...
     */

    // ...

    Vkovic\LaravelSettings\Providers\LaravelSettingsServiceProvider::class,

    // ...
];
```

Run migrations to create table which will be used to store our settings:

```bash
php artisan migrate
```

### Facade

Register facade in app config file:

```php
// File: config/app.php

// ...

'aliases' => [

    // ...

    'Settings' => \Vkovic\LaravelSettings\Facades\SettingsFacade::class,
]
```

## Usage: Simple Examples

Let's create and retrieve some settings:

```php
// Set setting value as string
Settings::set('foo', 'bar');

// Get setting value
Settings::get('foo')) // : 'bar'

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

Settings::get('age')) // : 35
Settings::get('temperature')) // : 24.7
Settings::get('value'); // : null
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
