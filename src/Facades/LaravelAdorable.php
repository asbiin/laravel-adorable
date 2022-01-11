<?php

namespace LaravelAdorable\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string get(string $size, string $uuid)
 *
 * @see \LaravelAdorable\Service\LaravelAdorable
 */
class LaravelAdorable extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \LaravelAdorable\Adorable\LaravelAdorable::class;
    }
}
