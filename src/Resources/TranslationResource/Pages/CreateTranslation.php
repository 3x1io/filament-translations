<?php

namespace io3x1\FilamentTranslations\Resources\TranslationResource\Pages;

use io3x1\FilamentTranslations\Resources\TranslationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTranslation extends CreateRecord
{
    protected static string $resource = TranslationResource::class;

    protected function getTitle(): string
    {
        return trans('filament-translations::translation.title.create');
    }
}
