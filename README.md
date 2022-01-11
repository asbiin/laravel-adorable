Adorable Avatar for Laravel
============================

LaravelAdorable is an library to generate nice avatars on Laravel.

[![Latest Version](https://img.shields.io/packagist/v/asbiin/laravel-adorable.svg?style=flat-square)](https://github.com/asbiin/laravel-adorable/releases)
[![Downloads](https://img.shields.io/packagist/dt/asbiin/laravel-adorable.svg?style=flat-square)](https://packagist.org/packages/asbiin/laravel-adorable)
[![GitHub Workflow Status](https://img.shields.io/github/workflow/status/asbiin/laravel-adorable/Unit%20tests?style=flat-square)](https://github.com/asbiin/laravel-adorable/actions?query=branch%3Amain)
[![Sonar Quality Gate](https://img.shields.io/sonar/quality_gate/asbiin_laravel-adorable?server=https%3A%2F%2Fsonarcloud.io&style=flat-square)](https://sonarcloud.io/dashboard?id=asbiin_laravel-adorable)
[![Coverage Status](https://img.shields.io/sonar/https/sonarcloud.io/asbiin_laravel-adorable/coverage.svg?style=flat-square)](https://sonarcloud.io/dashboard?id=asbiin_laravel-adorable)


# Installation

You may use Composer to install this package into your Laravel project:

``` bash
composer require asbiin/laravel-adorable
```

You don't need to add this package to your service providers.

## Support

This package supports Laravel 8 and newer, and has been tested with php 7.4 and newer versions.


## Configuration

You can publish the LaravelAdorable configuration in a file named `config/adorable.php`.
Just run this artisan command:

```sh
php artisan vendor:publish --tag="laraveladorable-config"
```


# Usage

Use `LaravelAdorable` facade to generate avatar:

```php
use Illuminate\Support\Str;
use LaravelAdorable\Facades\LaravelAdorable;

...
  $size = 200;
  $hash = Str::uuid();
  $dataUrl = LaravelAdorable::get($size, $hash);
```

# License

Author: [Alexis Saettler](https://github.com/asbiin)

Copyright © 2022.

Licensed under the MIT License. [View license](/LICENSE).

# Inspiration

This work is mainly inspired by [itsthatguy/avatars-api-middleware](https://github.com/itsthatguy/avatars-api-middleware) (MIT License).
