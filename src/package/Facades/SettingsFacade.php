<?php

namespace Vkovic\LaravelSettings\Facades;

use Illuminate\Support\Facades\Facade;

class SettingsFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'vkovic.laravel-settings';
    }
}