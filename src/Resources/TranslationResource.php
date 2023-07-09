<?php

namespace io3x1\FilamentTranslations\Resources;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use io3x1\FilamentTranslations\Models\Translation;
use io3x1\FilamentTranslations\Resources\TranslationResource\Pages;

class TranslationResource extends Resource
{
    protected static ?string $model = Translation::class;

    protected static ?string $slug = 'translations';

    protected static ?string $recordTitleAttribute = 'key';

    protected static function getNavigationLabel(): string
    {
        return trans('filament-translations::translation.label');
    }

    protected static function getNavigationGroup(): ?string
    {
        return config('filament-translations.languages-switcher-menu.group', 'Translations');
    }

    protected static function getNavigationIcon(): string
    {
        return config('filament-translations.languages-switcher-menu.icon', 'heroicon-o-translate');
    }

    protected function getTitle(): string
    {
        return trans('filament-translations::translation.title.home');
    }

    public static function form(Form $form): Form
    {
        $schema = [];

        foreach (config('filament-translations.locals') as $key => $lang) {
            $schema[] = Forms\Components\RichEditor::make('text.'.$key)
                ->label(trans('filament-translations::translation.lang.'.$key))
                ->rules(["regex:/^(?!.*<script>).+$/"])
                ->required();
        }

        return $form
            ->schema([
                Forms\Components\TextInput::make('group')
                    ->label(trans('filament-translations::translation.group'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('namespace')
                    ->label(trans('filament-translations::translation.namespace'))
                    ->required()
                    ->default('*')
                    ->maxLength(255),
                Forms\Components\TextInput::make('key')
                    ->label(trans('filament-translations::translation.key'))
                    ->required()
                    ->maxLength(255),

                Forms\Components\Builder\Block::make('text')
                    ->label(trans('filament-translations::translation.text'))
                    ->schema($schema),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('group')
                    ->label(trans('filament-translations::translation.group'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('key')
                    ->label(trans('filament-translations::translation.key'))
                    ->sortable()->searchable(),
                Tables\Columns\TextColumn::make('text')->label(trans('filament-translations::translation.text')),
                Tables\Columns\TextColumn::make('created_at')->label(trans('filament-translations::global.created_at'))
                    ->dateTime()->toggleable(),
                Tables\Columns\TextColumn::make('updated_at')->label(trans('filament-translations::global.updated_at'))
                    ->dateTime()->toggleable(),
            ])
            ->filters([
                //
            ]);
    }

    public static function getPages(): array
    {
        if (config('filament-translations.modal')) {
            return [
                'index' => Pages\ManageTranslations::route('/'),
            ];
        } else {
            return [
                'index' => Pages\ListTranslations::route('/'),
                'create' => Pages\CreateTranslation::route('/create'),
                'edit' => Pages\EditTranslation::route('/{record}/edit'),
            ];
        }
    }
}
