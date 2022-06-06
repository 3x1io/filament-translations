<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Paths
    |--------------------------------------------------------------------------
    |
    | add path that will be show to the scaner to catch lanuages tags
    |
    */
    "paths" => [
        app_path(),
        resource_path('views'),
        base_path('vendor')
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Excluded paths
    |--------------------------------------------------------------------------
    |
    | Put here any folder that you want to exclude that is inside of paths
    |
    */
    
    "excludedPaths" => [
    ],


    /*
    |--------------------------------------------------------------------------
    | Locals
    |--------------------------------------------------------------------------
    |
    | add the locals that will be show on the languages selector
    |
    */
    "locals" => [
        "en" => "English",
        "ar" => "Arabic",
        "pt_BR" => "Português (Brasil)",
        "my" => "Burmese"
    ],

    /*
    |--------------------------------------------------------------------------
    | Show Switcher
    |--------------------------------------------------------------------------
    |
    | show switcher item on the navigation menu
    |
    */
    "show-switcher" => true,

    /*
    |--------------------------------------------------------------------------
    | Switcher
    |--------------------------------------------------------------------------
    |
    | the lanuages of the switcher navigation item must be 2
    |
    */
    "switcher" => [
        "ar",
        "en",
    ],

    /*
    |--------------------------------------------------------------------------
    | Switcher Item Option
    |--------------------------------------------------------------------------
    |
    | custome switcher menu item
    |
    */

    "languages-switcher-menu" => [
        "group" => "Translations",
        "icon" => "heroicon-o-translate",
        "sort" => 10,
        "url" => 'admin/translations/change'
    ],

    /*
    |--------------------------------------------------------------------------
    | Modal
    |--------------------------------------------------------------------------
    |
    | use simple modal resource for the translation resource
    |
    */
    "modal" => true,

];
