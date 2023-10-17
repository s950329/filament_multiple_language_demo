<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Translation extends BaseModel
{
    protected $fillable = [
        'translatable_type',
        'translatable_id',
        'locale',
        'column',
        'value'
    ];

    public static function translatableMap()
    {
        return [
            Patient::class => __('Patient'),
        ];
    }

    public function translatable(): MorphTo
    {
        return $this->morphTo();
    }

    public static function localeMap()
    {
        return array_map(function ($item) {
            return $item['native'];
        }, config('filament-language-switch.locales'));
    }
}
