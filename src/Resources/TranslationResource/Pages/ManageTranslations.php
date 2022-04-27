<?php

namespace io3x1\FilamentTranslations\Resources\TranslationResource\Pages;

use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Actions\ButtonAction;
use Filament\Resources\Pages\ManageRecords;
use io3x1\FilamentTranslations\Services\SaveScan;
use io3x1\FilamentTranslations\Resources\TranslationResource;


class ManageTranslations extends ManageRecords
{
    protected static string $resource = TranslationResource::class;

    protected function getTitle(): string
    {
        return trans('filament-translations::translation.title.home');
    }

    protected function getActions(): array
    {
        return [
            ButtonAction::make('scan')
                ->icon('heroicon-o-document-search')
                ->action('scan')
                ->label(trans('filament-translations::translation.scan')),
            ButtonAction::make('settings')
                ->label(trans('filament-translations::translation.modal.setting'))
                ->icon('heroicon-o-cog')
                ->modalHeading(trans('filament-translations::translation.modal.heading'))
                ->modalButton(trans('filament-translations::translation.modal.button'))
                ->form([
                    Select::make('language')
                        ->label(trans('filament-translations::translation.modal.select'))
                        ->default(auth()->user()->lang)
                        ->options(config('filament-translations.locals'))
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $user = User::find(auth()->user()->id);

                    $user->lang = $data['language'];
                    $user->save();

                    session()->flash('notification', [
                        'message' => __(trans('filament-translations::translation.notification') . $user->lang),
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

        $this->notify('success', trans('filament-translations::translation.loaded'));
    }
}
