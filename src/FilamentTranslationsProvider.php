<?php

namespace io3x1\FilamentTranslations;

use Filament\Navigation\UserMenuItem;
use Illuminate\Support\ServiceProvider;
use Filament\PluginServiceProvider;
use io3x1\FilamentTranslations\Resources\TranslationResource;
use Filament\Navigation\NavigationItem;
use Filament\Facades\Filament;
use Spatie\LaravelPackageTools\Package;


class FilamentTranslationsProvider extends PluginServiceProvider
{

    public function configurePackage(Package $package): void
    {
        $package->name('filament-translations');
    }

    protected array $resources = [
        TranslationResource::class,
    ];

    public function boot(): void
    {
        parent::boot();

        //Load translations
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'filament-translations');

        //Publish Lang
        $this->publishes([
            __DIR__ . '/../resources/lang' => resource_path('lang/vendor/filament-translations'),
        ], 'filament-translations');

        //Publish Config
        $this->publishes([
            __DIR__ . '/../config' => config_path(),
        ], 'filament-translations-config');

        //Register Routes
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        //Register Migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        //Check Show Switcher
        if (config('filament-translations.show-switcher')) {
            Filament::serving(function () {
                if(auth()->user()){
                    app()->setLocale(auth()->user()->lang);
                }
                if(config('filament-translations.languages-switcher-menu.position') === 'navigation'){
                    Filament::registerNavigationItems([
                        NavigationItem::make()
                            ->group(config('filament-translations.languages-switcher-menu.group'))
                            ->icon(config('filament-translations.languages-switcher-menu.icon'))
                            ->label(trans('filament-translations::translation.menu'))
                            ->sort(config('filament-translations.languages-switcher-menu.sort'))
                            ->url((string)url(config('filament-translations.languages-switcher-menu.url'))),
                    ]);
                }
                else if(config('filament-translations.languages-switcher-menu.position') === 'user'){
                    Filament::registerUserMenuItems([
                        UserMenuItem::make()
                            ->icon(config('filament-translations.languages-switcher-menu.icon'))
                            ->label(trans('filament-translations::translation.menu'))
                            ->sort(config('filament-translations.languages-switcher-menu.sort'))
                            ->url((string)url(config('filament-translations.languages-switcher-menu.url'))),
                    ]);
                }

                Filament::registerNavigationGroups([
                    config('filament-translations.languages-switcher-menu.group')
                ]);
            });
        }
    }
}
