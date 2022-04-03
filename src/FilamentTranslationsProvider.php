<?php

namespace io3x1\FilamentTranslations;

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

        if (config('filament-translations.show-switcher')) {
            Filament::registerNavigationItems([
                NavigationItem::make()
                    ->group(config('filament-translations.languages-switcher-menu.group'))
                    ->icon(config('filament-translations.languages-switcher-menu.icon'))
                    ->label(trans('translation.menu'))
                    ->sort(config('filament-translations.languages-switcher-menu.sort'))
                    ->url(url('admin/translations/change')),
            ]);

            Filament::registerNavigationGroups([
                config('filament-translations.languages-switcher-menu.group')
            ]);
        }

        $this->publishes([
            __DIR__ . '/../resources' => resource_path(),
        ], 'filament-translations');

        $this->publishes([
            __DIR__ . '/../config' => config_path(),
        ], 'filament-translations-config');

        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}
