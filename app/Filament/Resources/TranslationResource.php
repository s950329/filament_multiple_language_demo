<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TranslationResource\Pages;
use App\Models\Patient;
use App\Models\Translation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TranslationResource extends Resource
{
    protected static ?string $model = Translation::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('translatable_type')
                    ->label(__('Translatable Type'))
                    ->options(function () {
                        return Translation::translatableMap();
                    })
                    ->required()
                    ->live(),
                Forms\Components\Select::make('translatable_id')
                    ->label(__('Translatable ID'))
                    ->options(function (Get $get) {
                        switch ($get('translatable_type')) {
                            case Patient::class:
                                return Patient::all()->pluck('name', 'id');
                            default:
                                return [];
                        }
                    })
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('locale')
                    ->label(__('Locale'))
                    ->options(Translation::localeMap())
                    ->required(),
                Forms\Components\Select::make('column')
                    ->label(__('Column'))
                    ->options(function (Get $get) {
                        return $get('translatable_type') ?
                            $get('translatable_type')::getTranslatableColumns() : [];
                    })
                    ->required(),
                Forms\Components\Textarea::make('value')
                    ->label(__('Value'))
                    ->required()
                    ->maxLength(255)
                    ->columnSpan('full'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('translatable_type')
                    ->label(__('Translatable Type'))
                    ->formatStateUsing(fn(string $state): string => Translation::translatableMap()[$state] ?? $state)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('translatable_id')
                    ->label(__('Translatable ID'))
                    ->formatStateUsing(function (?Model $record) {
                        switch (get_class($record->translatable)) {
                            case Patient::class:
                                return $record->translatable->name;
                            default:
                                return $record->translatable_id;
                        }
                    })
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('locale')
                    ->label(__('Locale')),
                Tables\Columns\TextColumn::make('column')
                    ->label(__('Column'))
                    ->formatStateUsing(fn($state, $record): string => $record->translatable->getTranslatableColumns()[$state] ?? $state),
                Tables\Columns\TextColumn::make('value')
                    ->label(__('Value'))
                    ->words(10),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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

    public static function getLabel(): ?string
    {
        return __('Translations');
    }
}
