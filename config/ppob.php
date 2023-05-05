<?php

return [

    'default' => 'account-portalpulsa',

    /*
    |--------------------------------------------------------------------------
    | Selenium Service
    |--------------------------------------------------------------------------
    |
    | To create selenium chrome based browser, you must specify its host
    |
    */

    'accounts' => [
        'account-mobilepulsa' => [
            'provider' => 'mobile-pulsa',
            'username' => env('MOBILEPULSA_USERNAME'),
            'apikey' => env('MOBILEPULSA_APIKEY')
        ],
        'account-portalpulsa' => [
            'provider' => 'portal-pulsa',
            'username' => env('PORTALPULSA_USERNAME'),
            'apikey' => env('PORTALPULSA_APIKEY'),
            'secret' => env('PORTALPULSA_SECRET')
        ],
        'account-javah2h' => [
            'provider' => 'javah2h',
            'username' => env('JAVAH2H_USERNAME'),
            'apikey' => env('JAVAH2H_APIKEY'),
            'secret' => env('JAVAH2H_SECRET')
        ],
        'account-tripay' => [
            'provider' => 'tripay',
            'apikey' => env('TRIPAY_APIKEY'),
            'pin' => env('TRIPAY_PIN')
        ],
        'account-indoh2h' => [
            'provider' => 'indo-h-2-h',
            'username' => env('INDOH2H_USERNAME'),
            'apikey' => env('INDOH2H_APIKEY'),
        ],
        'account-digiflazz' => [
            'provider' => 'digiflazz',
            'username' => env('DIGIFLAZZ_USERNAME'),
            'apikey' => env('APP_ENV')=='production' ? env('DIGIFLAZZ_PROD_APIKEY') : env('DIGIFLAZZ_DEV_APIKEY'),
        ],
    ]
];
