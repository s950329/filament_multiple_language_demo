<?php

namespace App\Observers;

use App\Models\Translation;

class TranslationObserver
{
    public function saving(Translation $translation)
    {
        if($translation->isDirty('translatable_id')) {
            $translation = Translation::where([
                'translatable_type' => $translation->translatable_type,
                'translatable_id' => $translation->translatable_id,
                'locale' => $translation->locale,
                'column' => $translation->column,
            ])->first();

            if ($translation) {
                $translation->update('value', $translation->value);
                return false;
            }
        }
    }
}
