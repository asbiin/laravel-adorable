<?php

namespace LaravelAdorable\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \LaravelAdorable\Service\LaravelAdorable
 */
class LaravelAdorable extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \LaravelAdorable\Adorable\LaravelAdorable::class;
    }
}
