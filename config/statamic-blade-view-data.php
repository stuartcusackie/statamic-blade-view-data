<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Views
    |--------------------------------------------------------------------------
    |
    | Define the blade views that provide Statamic data (e.g. page, site, globals).
    |
    */

    'views' => [
        'pages.*',
        'blog.*'
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Navs
    |--------------------------------------------------------------------------
    |
    | Add all nav handles that you want to be able to access from your views.
    | You can also set the specific nav params that you require, and enabled
    | caching for better performance. Remember, each nav adds processing time
    | and cached navs should be forgetten using an Event Service Provider.
    |
    */

    'navs' => [
        'main' => [
            'select' => 'children|title|url|is_current|hide_from_header',
            'cache' => true
        ]
    ]

];