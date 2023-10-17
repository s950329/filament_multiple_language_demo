<?php

namespace App\Filament\Resources\TranslationResource\Pages;

use App\Filament\Resources\TranslationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditTranslation extends EditRecord
{
    protected static string $resource = TranslationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $modelData = $data;

        unset($modelData['translatable_type'], $modelData['translatable_id'], $modelData['locale']);

        foreach ($modelData as $key => $value) {
            $model = static::getModel()::updateOrcreate([
                'translatable_type' => $data['translatable_type'],
                'translatable_id' => $data['translatable_id'],
                'locale' => $data['locale'],
                'column' => $key,
            ], [
                'value' => $value,
            ]);
        }

        return $model;
    }

}
