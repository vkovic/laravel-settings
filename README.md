# Laravel Settings

[![Build](https://api.travis-ci.org/vkovic/laravel-settings.svg?branch=master)](https://travis-ci.org/vkovic/laravel-settings)
[![Downloads](https://poser.pugx.org/vkovic/laravel-settings/downloads)](https://packagist.org/packages/vkovic/laravel-settings)
[![Stable](https://poser.pugx.org/vkovic/laravel-settings/v/stable)](https://packagist.org/packages/vkovic/laravel-settings)
[![License](https://poser.pugx.org/vkovic/laravel-settings/license)](https://packagist.org/packages/vkovic/laravel-settings)

### Neat way to handle app specific settings

If you want to save application specific settings and you dont want to create another table/model/logic,
this package is for you.

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

## Usage: Basics

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

This is a very basic way of using laravel-settings package.
This package is actually extension to
[vkovic/laravel-meta](https://github.com/vkovic/laravel-meta)
and it's uses the same logic, so you can
[check further usage examples there](https://github.com/vkovic/laravel-meta#usage-simple-examples).