<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TranslationResource\Pages;
use App\Models\Patient;
use App\Models\Translation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
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
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        // 如果同时存在的话，就把翻译的值赋值给表单
                        if ($get('translatable_type') && $get('translatable_id') && $get('locale')) {
                            $model = $get('translatable_type')::with(['translations' => function ($query) use ($get) {
                                $query->where('locale', $get('locale'));
                            }])->find($get('translatable_id'));

                            if ($model->translations->count()) {
                                foreach ($model->translations as $translation) {
                                    $set($translation->column, $translation->value);
                                }
                            } else {
                                foreach ($get('translatable_type')::getTranslatableColumns() as $column => $val) {
                                    $set($column, null);
                                };
                            }
                        }
                    }),

                Forms\Components\Select::make('locale')
                    ->label(__('Locale'))
                    ->options(Translation::localeMap())
                    ->required()
                    ->live()
                    ->afterStateUpdated(function(Get $get, Set $set) {
                        if ($get('translatable_type') && $get('translatable_id') && $get('locale')) {
                            $model = $get('translatable_type')::with(['translations' => function ($query) use ($get) {
                                $query->where('locale', $get('locale'));
                            }])->find($get('translatable_id'));

                            if ($model->translations->count()) {
                                foreach ($model->translations as $translation) {
                                    $set($translation->column, $translation->value);
                                }
                            } else {
                                foreach ($get('translatable_type')::getTranslatableColumns() as $column => $val) {
                                    $set($column, null);
                                };
                            }
                        }
                    }),

                Forms\Components\Section::make(__('Translation Form'))
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('Name'))
                            ->required()
                            ->maxLength(255),
                    ])
                    ->hidden(fn(Get $get) => $get('translatable_type') != Patient::class),
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
                    ->label(__('Locale'))
                    ->formatStateUsing(fn($state, $record): string => Translation::localeMap()[$state] ?? $state),
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->groupBy('translatable_id', 'translatable_type', 'locale');
    }
}
