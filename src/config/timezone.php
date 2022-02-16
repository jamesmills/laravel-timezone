<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Flash messages
    |--------------------------------------------------------------------------
    |
    | Here you may configure if to use the laracasts/flash package for flash
    | notifications when a users timezone is set.
    | options [off, laravel, laracasts, mercuryseries, spatie, mckenziearts, tall-toasts]
    |
    */

    'flash' => 'laravel',

    /*
    |--------------------------------------------------------------------------
    | Overwrite Existing Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may configure if you would like to overwrite existing
    | timezones if they have been already set in the database.
    | options [true, false]
    |
    */

    'overwrite' => true,

    /*
    |--------------------------------------------------------------------------
    | Overwrite Default Format
    |--------------------------------------------------------------------------
    |
    | Here you may configure if you would like to overwrite the
    | default format.
    |
    */

    'format' => 'jS F Y g:i:a',
    
    /*
    |--------------------------------------------------------------------------
    | Enable translated output
    |--------------------------------------------------------------------------
    |
    | Here you may configure if you would like to use translated output.
    |
    */

    'enableTranslation' => false,

    /*
    |--------------------------------------------------------------------------
    | Lookup Array
    |--------------------------------------------------------------------------
    |
    | Here you may configure the lookup array whom it will be used to fetch the user remote address.
    | When a key is found inside the lookup array that key it will be used.
    |
    */

    'lookup' => [
        'server' => [
            'REMOTE_ADDR',
        ],
        'headers' => [

        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Message
    |--------------------------------------------------------------------------
    |
    | Here you may configure the message shown to the user when the timezone is set.
    | Be sure to include the %s which will be replaced by the detected timezone.
    | e.g. We have set your timezone to America/New_York
    |
    */

    'message' => 'We have set your timezone to %s',
];
