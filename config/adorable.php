<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Image Driver
    |--------------------------------------------------------------------------
    | Avatar use Intervention Image library to process image.
    | Meanwhile, Intervention Image supports "GD Library" and "Imagick" to process images
    | internally. You may choose one of them according to your PHP
    | configuration. By default PHP's "Imagick" implementation is used.
    |
    | Supported: gd, imagick
    |
    */
    'driver' => env('IMAGE_DRIVER', 'gd'),

    /*
    |--------------------------------------------------------------------------
    | Image shape
    |--------------------------------------------------------------------------
    |
    | Supported: circle, square
    */

    'shape' => 'square',

    /*
    |--------------------------------------------------------------------------
    | Background colors
    |--------------------------------------------------------------------------
    */

    'colors' => [
        '#81bef1',
        '#ad8bf2',
        '#bff288',
        '#de7878',
        '#a5aac5',
        '#6ff2c5',
        '#f0da5e',
        '#eb5972',
        '#f6be5d',
    ],

    /*
    |--------------------------------------------------------------------------
    | Border behavior
    |--------------------------------------------------------------------------
    */

    'border' => [
        'size' => 0,

        // border color, available value are:
        // 'white' (white border)
        // 'background' (same as background color)
        // or any valid hex ('#aabbcc')
        'color' => 'background',
    ],

];
