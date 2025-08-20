<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Driver Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the driver below you wish to use as
    | your default driver for all work. Of course, you may use many
    | drivers at once using the manager class.
    |
    */

    'default' => env('SQIDS_DEFAULT', 'default'),

    /*
    |--------------------------------------------------------------------------
    | Hashids Drivers
    |--------------------------------------------------------------------------
    |
    | Here are each of the drivers setup for your application. Example
    | configuration has been included, but you may add as many drivers as
    | you would like.
    |
    */

    'drivers' => [

        'default' => [
            'pad' => env('SQIDS_DEFAULT_PAD', ''),
            'length' => env('SQIDS_DEFAULT_LENGTH', '6'),
            // 'blocklist' => env('SQIDS_DEFAULT_BLOCK_LIST', []),
            // 'alphabet' => env('SQIDS_DEFAULT_ALPHABET', 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'),
        ],

        'tracking' => [
            'pad' => env('SQIDS_TRACKING_PAD', '5000'),
            'length' => env('SQIDS_TRACKING_LENGTH', '8'),
            'alphabet' => env('SQIDS_TRACKING_ALPHABET', '1234567890'),
        ],

    ],

];
