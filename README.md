# Filament Translations Manager

Translations With DB & UI

## Installation

You can install the package via composer:

```bash
composer require 3x1io/filament-translations
```

Run migration:

```bash
php artisan vendor:publish --tag="filament-translations"
php artisan migrate
```

In `config/app.php` (Laravel) or `bootstrap/app.php` (Lumen) you should replace Laravel's translation service provider

```php
Illuminate\Translation\TranslationServiceProvider::class,
```

by the one included in this package:

```php
Spatie\TranslationLoader\TranslationServiceProvider::class,
```

## Add Language Middleware

go to app/Http/Kernal.php and add new middleware to $middlewareGroups

```php
    'web' => [
        //...
        \io3x1\FilamentTranslations\Http\Middleware\LanguageMiddleware::class,
    ],
```

go to config/filament.php and add middleware to middleware auth array

```php
    'middleware' => [
        'auth' => [
            //...
            \io3x1\FilamentTranslations\Http\Middleware\LanguageMiddleware::class
        ],
        //...
    ];
```

and now clear cache

```bash
php artisan optimize:clear
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

-   [3x1](https://github.com/3x1io)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
