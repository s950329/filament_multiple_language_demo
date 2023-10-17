<?php

namespace App\Models;

trait Translatable
{
    public function translations()
    {
        return $this->morphMany(Translation::class, 'translatable');
    }

    protected static function booted(): void
    {
        static::retrieved(function (BaseModel $model) {
            $model->load(['translations' => function($query) {
                $query->where('locale', app()->getLocale());
            }]);

            foreach ($model->translations as $translation) {
                if ($translation->column === 'name') {
                    $model->name = $translation->value;
                }
            }
        });
    }
}
