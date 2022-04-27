<?php

namespace io3x1\FilamentTranslations\Resources\TranslationResource\Pages;

use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Pages\Actions\ButtonAction;
use Filament\Resources\Pages\ListRecords;
use io3x1\FilamentTranslations\Services\SaveScan;
use io3x1\FilamentTranslations\Resources\TranslationResource;

class ListTranslations extends ListRecords
{

    protected static string $resource = TranslationResource::class;


    protected function getTitle(): string
    {
        return trans('filament-translations::translation.title.list');
    }

    protected function getActions(): array
    {
        return [
            ButtonAction::make('scan')->action('scan')->label(trans('translation.scan')),
            ButtonAction::make('settings')
                ->label('Settings')
                ->icon('heroicon-o-cog')
                ->form([
                    Select::make('language')
                        ->label('Language')
                        ->default(auth()->user()->lang)
                        ->options(config('filament-translations.locals'))
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $user = User::find(auth()->user()->id);

                    $user->lang = $data['language'];
                    $user->save();

                    session()->flash('notification', [
                        'message' => __(trans('translation.notification') . $user->lang),
                        'status' => "success",
                    ]);

                    redirect()->to('admin/translations');
                }),
        ];
    }

    public function scan()
    {
        $scan = new SaveScan();
        $scan->save();

        $this->notify('success', 'Translation Has Been Loaded');
    }
}
