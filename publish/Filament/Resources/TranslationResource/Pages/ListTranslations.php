<?php

namespace App\Filament\Resources\TranslationResource\Pages;

use io3x1\FilamentTranslations\Services\SaveScan;
use Filament\Pages\Actions\ButtonAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\TranslationResource;

class ListTranslations extends ListRecords
{

    protected static string $resource = TranslationResource::class;

    protected function getTitle(): string
    {
        return trans('translation.title');
    }

    protected function getActions(): array
    {
        return [
            ButtonAction::make('scan')->action('scan')->label(trans('translation.scan')),
        ];
    }

    public function scan()
    {
        $scan = new SaveScan();
        $scan->save();

        $this->notify('success', 'Translation Has Been Loaded');
    }
}
