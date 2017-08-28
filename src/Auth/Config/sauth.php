<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Company Name
    |--------------------------------------------------------------------------
    |
    | This value is the name that will be shown on the 2FA.
    |
    */

    'name' => env('APP_NAME', 'SmoDav Auth'),

    /*
    |--------------------------------------------------------------------------
    | Window
    |--------------------------------------------------------------------------
    |
    | This determines the length of time that a code is valid. Setting it to 0
    | makes it valid only for 30 seconds, 1 sets it to 60 seconds and so forth.
    |
    */

    'window' => 1,

    /*
    |--------------------------------------------------------------------------
    | Strict Mode
    |--------------------------------------------------------------------------
    |
    | This determines whether strict mode should be activated. When active, once a
    | code has been used, it cannot be reused.
    |
    */

    'strict' => false,
];
