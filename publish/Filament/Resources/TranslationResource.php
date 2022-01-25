<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use io3x1\FilamentTranslations\Models\Translation;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use App\Filament\Resources\TranslationResource\Pages;


class TranslationResource extends Resource
{
    protected static ?string $model = Translation::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe';

    protected static ?string $recordTitleAttribute = 'key';

    protected static ?string $navigationGroup = 'Translations';

    protected static function getNavigationLabel(): string
    {
        return trans('translation.label');
    }

    public static function form(Form $form): Form
    {
        $schema = [];

        foreach (config('filament-translations.locals') as $lang) {
            array_push(
                $schema,
                Forms\Components\TextInput::make('text.' . $lang)
                    ->label(ucfirst($lang))
                    ->required(),
            );
        }
        return $form
            ->schema([
                Forms\Components\TextInput::make('group')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('namespace')
                    ->required()
                    ->default('*')
                    ->maxLength(255),
                Forms\Components\TextInput::make('key')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Builder\Block::make('text')
                    ->schema($schema),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('group')
                    ->label(trans('translation.group'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('key')
                    ->label(trans('translation.key'))
                    ->sortable()->searchable(),
                Tables\Columns\TextColumn::make('text')->label(trans('translation.text')),
                Tables\Columns\TextColumn::make('created_at')->label(trans('global.created_at'))
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')->label(trans('global.updated_at'))
                    ->dateTime(),
            ])
            ->filters([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTranslations::route('/'),
            'create' => Pages\CreateTranslation::route('/create'),
            'edit' => Pages\EditTranslation::route('/{record}/edit'),
        ];
    }
}
