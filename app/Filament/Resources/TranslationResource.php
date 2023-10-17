<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TranslationResource\Pages;
use App\Filament\Resources\TranslationResource\RelationManagers;
use App\Models\Translation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
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
                    ->required(),
                Forms\Components\TextInput::make('translatable_id')
                    ->label(__('Translatable ID'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('locale')
                    ->label(__('Locale'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('column')
                    ->label(__('Column'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('value')
                    ->label(__('Value'))
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('translatable_type')
                    ->label(__('Translatable Type'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('translatable_id'),
                Tables\Columns\TextColumn::make('locale'),
                Tables\Columns\TextColumn::make('column'),
                Tables\Columns\TextColumn::make('value')
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
